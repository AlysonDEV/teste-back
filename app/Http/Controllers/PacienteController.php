<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Http\Requests\PacienteRequest;

use Brazanation\Documents\Cpf as CpfValidation;
use Brazanation\Documents\Exception\InvalidDocument as  InvalidDocumentException;


use App\Models\Paciente;



class PacienteController extends Controller
{

    // Método no PacienteController para listar pacientes ativos

    public function insert(Request $req)
    {
        try {


            $validator = Validator::make($req->all(), [
                'nome' => 'required|string|max:255',
                'dt_nascimento' => 'required|date_format:Y-m-d',
                'cpf' => [
                    'required',
                    'string',
                    'max:11',
                    'unique:pacientes,cpf',
                    function ($attribute, $value, $fail) {
                        try {
                            $cpf = new CpfValidation($value);
                        } catch (InvalidDocumentException $e) {
                            $fail('O campo ' . $value . ' está inválido' . $e->getMessage());
                        }
                    }

                ],
                'telefone' => 'required|string|max:11',
                'foto' => 'required|image',
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'unique' => 'O campo :attribute já foi utilizado',
                'string' => 'O campo :attribute deve ser uma string',
                'max' => 'O campo :attribute não pode ter mais que :max caracteres',
                'date' => 'O campo :attribute deve ser uma data válida',
                'image' => 'O arquivo enviado para o campo :attribute não é uma imagem válida',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $paciente = new Paciente;

            $paciente->nome = $req->nome;
            $paciente->dt_nascimento = Carbon::parse($req->dt_nascimento)->toDateString();
            $paciente->cpf = $req->cpf;
            $paciente->telefone = $req->telefone;

            // Quardar imagem e renomear ela para armazenar
            $imagem = $req->file('foto');
            // Gerar um cpf para o nome do arquivo
            $nomeImagem = $paciente->cpf . '.' . $imagem->getClientOriginalExtension();

            // Colocar a imagem na pasta pública
            Storage::putFileAs('/', $imagem, $nomeImagem);
            $imagem->move(public_path('imagens'), $nomeImagem);
            $paciente->foto = $nomeImagem;


            $paciente->save();

            return response()->json($paciente, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método no PacienteController para listar pacientes ativos

    public function listActive()
    {
        // $pacientes = Paciente::paginate(10);
        $pacientes = Paciente::all();

        return response()->json($pacientes);
    }

    // Método no PacienteController para localizar o paciente pelo ID

    public function getByID($id)
    {
        try {
            $paciente = Paciente::findOrFail($id);

            return response()->json($paciente);
        } catch (\Exception $e) {
            return response()->json(['error' => "Não foi possível localizar a o registro {$id}"], 500);
        }
    }

    // Método no PacienteController para localizar o paciente pelo CPF

    public function getByCPF($cpf)
    {
        try {
            $paciente = Paciente::where('cpf', $cpf)->get();

            return response()->json($paciente);
        } catch (\Exception $e) {
            return response()->json(['error' => "Não foi possível localizar a o registro {$cpf}"], 500);
        }
    }

    // Método no PacienteController para listar pacientes excluidos

    public function listDeleted()
    {
        $pacientes = Paciente::onlyTrashed()->get();
        return response()->json($pacientes);
    }

    // Método no PacienteController para deletar um paciente pelo ID 

    public function delete($id)
    {
        $paciente = Paciente::find($id);

        if ($paciente) {
            $paciente->delete();
            return response()->json(['message' => 'Paciente deletado com sucesso!']);
        } else {
            return response()->json(['error' => 'Paciente não encontrado.'], 404);
        }
    }

    // Método no PacienteController para restaurar um paciente excluído pelo ID

    public function restoreById($id)
    {
        try {
            // Busca o paciente excluído pelo ID
            $paciente = Paciente::onlyTrashed()->findOrFail($id);

            if (!$paciente) throw new \Exception("Registro não encontrado. {$id}");


            // Restaura o registro do paciente
            $paciente->restore();

            // Retorna a mensagem de sucesso
            return response()->json(['message' => 'Paciente restaurado com sucesso.'], 200);
        } catch (\Exception $e) {
            // Retorna uma mensagem de erro em caso de falha
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método no PacienteController para restaurar um paciente excluído pelo CPF

    public function restoreByCpf($cpf)
    {
        try {
            // Busca o paciente excluído pelo CPF
            $paciente = Paciente::onlyTrashed()->where('cpf', $cpf)->firstOrFail();

            if (!$paciente) throw new \Exception("Registro não encontrado. {$cpf}");

            // Restaura o registro do paciente
            $paciente->restore();

            // Retorna a mensagem de sucesso
            return response()->json(['message' => 'Paciente restaurado com sucesso.'], 200);
        } catch (\Exception $e) {
            // Retorna uma mensagem de erro em caso de falha
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método no PacienteController para excluir o paciente pelo ID permanentemente

    public function destroy($id)
    {
        try {

            // Busca o registro do paciente pelo o ID
            $paciente = Paciente::find($id);

            if (!$paciente) throw new \Exception("Registro não encontrado. {$id}");

            $nome = $paciente->nome;

            // Faz a exclusão da imagem no storage
            $imageName = $paciente->foto;
            $imagePath = public_path('imagens/' . $imageName);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            // Storage::disk('imagens')->delete($imageName);

            // Apagar o registro definitivamente
            $paciente->forceDelete();
            // $paciente->history()->forceDelete();

            // Retorna a mensagem de sucesso
            return response()->json(['message' => 'Excluido com sucesso usuario ' . $nome . ' ID:' . $id . ''], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
