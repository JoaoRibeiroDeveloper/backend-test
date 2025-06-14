<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    // É necessário adicionar validações mais coerentes no Request da aplicação, definindo os tipos de dados esperados (como string, int, etc). Isso evita que, por exemplo, um array seja enviado em um campo que espera um valor simples, prevenindo erros 500 no sistema causados por falhas de tipagem ou problemas ao persistir dados no banco.
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
