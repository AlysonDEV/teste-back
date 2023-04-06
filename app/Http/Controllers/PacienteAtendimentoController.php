<?php

namespace App\Http\Controllers;

use App\Models\PacienteAtendimento;
use Illuminate\Http\Request;

class PacienteAtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1, $limitPerPage = 10)
    {
        try {
            // $atendimentos = PacienteAtendimento::all();

            $atendimentos = PacienteAtendimento::rightJoin('pacientes', 'pacientes.id', '=', 'paciente_atendimentos.paciente_id')
                ->select('paciente_atendimentos.*', 'pacientes.nome', 'pacientes.cpf', 'pacientes.dt_nascimento')
                ->where('status', '=', 'Não atendido')
                ->paginate($limitPerPage, ['*'], 'page', $page);

            $data = [
                'atendimentos' => $atendimentos->items(),
                'currentPage' => $atendimentos->currentPage(),
                'lastPage' => $atendimentos->lastPage(),
                'perPage' => $atendimentos->perPage(),
                'total' => $atendimentos->total(),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $pacienteId)
    {
        try {
            $atendimento = new PacienteAtendimento();
            $atendimento->paciente_id = $pacienteId;
            $atendimento->t = $request->input('t');
            $atendimento->pas = $request->input('pas');
            $atendimento->pad = $request->input('pad');
            $atendimento->fc = $request->input('fc');
            $atendimento->fr = $request->input('fr');
            $atendimento->febre = $request->input('febre', false);
            $atendimento->coriza = $request->input('coriza', false);
            $atendimento->nariz_intupido = $request->input('nariz_intupido', false);
            $atendimento->cansaco = $request->input('cansaco', false);
            $atendimento->tosse = $request->input('tosse', false);
            $atendimento->dor_cabeca = $request->input('dor_cabeca', false);
            $atendimento->dores_corpo = $request->input('dores_corpo', false);
            $atendimento->mal_estar_geral = $request->input('mal_estar_geral', false);
            $atendimento->dor_garganta = $request->input('dor_garganta', false);
            $atendimento->dificuldade_respirar = $request->input('dificuldade_respirar', false);
            $atendimento->falta_paladar = $request->input('falta_paladar', false);
            $atendimento->falta_olfato = $request->input('falta_olfato', false);
            $atendimento->dificuldade_locomocao = $request->input('dificuldade_locomocao', false);
            $atendimento->diarreia = $request->input('diarreia', false);
            $atendimento->save();

            return response()->json($atendimento, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, $pacienteId)
    {
        try {
            // Busca o atendimento pelo ID e pelo ID do paciente

            $atendimento = PacienteAtendimento::where('id', $id)
                ->where('paciente_id', $pacienteId)
                ->firstOrFail();

            // Atualiza os campos do atendimento com os valores do request
            $atendimento->update($request->all());

            // Retorna a resposta de sucesso com o atendimento atualizado
            return response()->json($atendimento, 200);
        } catch (\Exception $e) {
            // Retorna a resposta de erro indicando que o atendimento não foi encontrado
            return response()->json(['error' => 'Atendimento não encontrado'], 404);
        }
    }

    /**
     * Destroy completamente do banco de dados as informações do atendimento.
     */
    public function destroy($id)
    {
        try {
            $atendimento = PacienteAtendimento::find($id);

            if (!$atendimento) throw new \Exception("Registro não encontrado. {$id}");

            $atendimento->forceDelete();


            return response()->json(['message' => 'Atendimento deletado com sucesso. ID: ' .  $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
