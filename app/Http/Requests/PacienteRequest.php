<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        $id = $this->route('paciente');
        $cpfRule = Rule::unique('pacientes', 'cpf');
        if ($id) {
            $cpfRule = $cpfRule->ignore($id);
        }

        return [
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date_format:Y-m-d',
            'cpf' => ['required', 'string', 'max:11', 'min:11', $cpfRule, 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/'],
            'telefone' => ['required', 'string', 'max:15', 'regex:/^\(\d{2}\) \d{5}\-\d{4}$/'],
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }
}
