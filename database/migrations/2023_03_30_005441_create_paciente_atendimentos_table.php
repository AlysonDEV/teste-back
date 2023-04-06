<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paciente_atendimentos', function (Blueprint $table) {
            $table->uuid('id')->primary('id');
            $table->uuid('paciente_id')->index('paciente_id');
            $table->timestamps();
            $table->string('status')->default('Não atendido');
            $table->double('t', 5, 2);
            $table->integer('pas');
            $table->integer('pad');
            $table->integer('fc');
            $table->integer('fr');

            $table->boolean('febre')->default(false);
            $table->boolean('coriza')->default(false);
            $table->boolean('nariz_intupido')->default(false);
            $table->boolean('cansaco')->default(false);
            $table->boolean('tosse')->default(false);
            $table->boolean('dor_cabeca')->default(false);
            $table->boolean('dores_corpo')->default(false);
            $table->boolean('mal_estar_geral')->default(false);
            $table->boolean('dor_garganta')->default(false);
            $table->boolean('dificuldade_respirar')->default(false);
            $table->boolean('falta_paladar')->default(false);
            $table->boolean('falta_olfato')->default(false);
            $table->boolean('dificuldade_locomocao')->default(false);
            $table->boolean('diarreia')->default(false);
            $table->softDeletes();

            $table->foreign('paciente_id')
                ->references('id')
                ->on('pacientes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente_atendimentos');
    }
};
