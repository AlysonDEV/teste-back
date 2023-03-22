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
    public function listar()
    {
        // $pacientes = Paciente::paginate(10);
        $pacientes = Paciente::all();

        return response()->json($pacientes);
    }

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

            // validar cpf
            // try {
            //     $cpf = new Cpf($req->cpf);
            //     if (!$cpf->isValid()) {
            //         throw new InvalidDocumentException('O CPF ' . $req->cpf . ' está inválido');
            //     }
            // } catch (InvalidDocumentException $e) {
            //     return response()->json(['error' => ': ' . $e->getMessage()], 400);
            // }

            $paciente = new Paciente;

            $paciente->nome = $req->nome;
            $paciente->dt_nascimento = Carbon::parse($req->dt_nascimento)->toDateString();
            $paciente->cpf = $req->cpf;
            $paciente->telefone = $req->telefone;

            // Quardar imagem e renomear ela para armazenar
            $imagem = $req->file('foto');
            // Gerar um cpf para o nome do arquivo
            $nomeImagem = $paciente->cpf . '.' . $imagem->getClientOriginalExtension();

            Storage::putFileAs('/', $imagem, $nomeImagem);

            $imagem->move(public_path('imagens'), $nomeImagem);
            $paciente->foto = $nomeImagem;


            $paciente->save();

            return response()->json($paciente, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
