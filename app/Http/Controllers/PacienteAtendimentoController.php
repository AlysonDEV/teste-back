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
        //
        $atendimentos = PacienteAtendimento::paginate($limitPerPage, ['*'], 'page', $page);

        return response()->json($atendimentos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PacienteAtendimento $pacienteAtendimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PacienteAtendimento $pacienteAtendimento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PacienteAtendimento $pacienteAtendimento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PacienteAtendimento $pacienteAtendimento)
    {
        //
    }
}
