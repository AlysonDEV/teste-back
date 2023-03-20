<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use Illuminate\Http\Request;

use App\Models\Paciente;


class PacienteController extends Controller
{
    public function listar()
    {
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


    public function insert(Request $req)
    {

        $paciente = new Paciente;

        $paciente->nome = $req->name;
        $paciente->dt_nascimento = "1988-01-19";
        $paciente->cpf = "02751811388";
        $paciente->telefone = "85992188113";
        $paciente->foto = "nulo";

        $paciente->save();


        // return redirect('/listar');return response()->json($paciente, 201);
        return response()->json($req["nome"], 201);
    }
}
