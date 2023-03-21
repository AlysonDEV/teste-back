<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Http\Requests\PacienteRequest;


use App\Models\Paciente;


class PacienteController extends Controller
{
    public function listar()
    {
        // $pacientes = Paciente::paginate(10);
        $pacientes = Paciente::all();

        return response()->json($pacientes);
    }

    public function cadastrar(PacienteRequest $req)
    {
        $data = $req->validated();


        if ($req->hasFile('foto') && $req->file('foto')->isValid()) {
            $file = $req->file('foto');
            $extension = $file->extension();
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs('public/fotos', $filename);
            $data['foto'] = Storage::url($path);
        }

        // if ($req->hasFile('foto') && $req->file('foto')->isValid()) {
        //     $file = $req->file('foto');
        //     $extension = $file->extension();
        //     $filename = Str::uuid() . '.' . $extension;
        //     $path = $file->storeAs('public/fotos', $filename);
        //     $data['foto'] = Storage::url($path);
        // }

        $paciente = Paciente::create($data);

        return response()->json($paciente, 201);
    }


    public function insert(Request $req)
    {
        // return response()->json($req, 201);


        try {
            $validator = Validator::make($req->all(), [
                'nome' => 'required|string|max:255',
                'dt_nascimento' => 'required|date',
                'cpf' => 'required|string|max:255',
                'telefone' => 'required|string|max:255',
                // 'foto' => 'required|file',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }


            $paciente = new Paciente;

            $paciente->nome = $req->nome;
            $paciente->dt_nascimento = Carbon::parse($req->dt_nascimento)->toDateString();
            $paciente->cpf = $req->cpf;
            $paciente->telefone = $req->telefone;

            if ($req->hasFile('foto')) {
                $imagem = $req->file('foto');
                // Gerar um cpf para o nome do arquivo
                $nomeImagem = $paciente->cpf . '.' . $imagem->getClientOriginalExtension();

                Storage::putFileAs('/', $imagem, $nomeImagem);

                $imagem->move(public_path('imagens'), $nomeImagem);
                $paciente->foto = $nomeImagem;
            } else {
                $paciente->foto = "nulo";
            }

            // dd($paciente);
            $paciente->save();

            // $apenas = $paciente->id;

            return response()->json($req, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
