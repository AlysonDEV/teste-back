<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Models\Paciente;


class PacienteController extends Controller
{
    public function listar(){
        // $pacientes = Paciente::paginate(10);
        $pacientes = Paciente::all();

        return response()->json($pacientes);
    }

    public function cadastrar(PacienteRequest $request)
    {
        $data = $request->validated();
        

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $extension = $file->extension();
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs('public/fotos', $filename);
            $data['foto'] = Storage::url($path);
        }

        $paciente = Paciente::create($data);

        return response()->json($paciente, 201);
    }


    public function insert(Request $req): RedirectResponse {

        $paciente = new Paciente;

        $paciente->nome = $req->nome;
        $paciente->dt_nacimento = $req->dt_nacimento;
        $paciente->cpf = $req->cpf;
        $paciente->telefone = $req->telefone;
        $paciente->foto = $req->foto;

        $paciente-save();


        return redirect('/listar');

    }
}
