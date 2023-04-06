<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Paciente extends Model
{
    use HasFactory, HasUuids, SoftDeletes;


    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function atendimentos()
    {
        return $this->hasMany(PacienteAtendimento::class, 'paciente_id');
    }
}
