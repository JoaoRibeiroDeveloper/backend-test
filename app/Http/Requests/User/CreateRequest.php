<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{

    //É necessário implementar validações básicas, como verificar se o documento está no formato correto e assegurar que o campo 'name' seja do tipo string. Isso previne o envio de dados inválidos para o sistema, garantindo a integridade das informações e evitando erros durante o processamento.
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'document_number' => 'required|regex:/[0-9]{11}/i',
            'name'            => 'required',
            'email'           => 'required|email',
            'password'        => 'required',
            'type'            => 'required|in:USER,VIRTUAL,MANAGER'
        ];
    }
}
