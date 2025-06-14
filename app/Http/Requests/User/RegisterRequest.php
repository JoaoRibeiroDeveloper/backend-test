<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    // Podem ser aplicadas duas melhorias no campo de documento (CPF/CNPJ) tanto do usuário quanto da empresa:

    // 1. Limpeza dos dados
    // Atualmente, o cliente precisa enviar o documento sem pontuação (sem pontos, traços ou barras).
    // Isso pode gerar problemas de integração, especialmente em sistemas B2B.

    // Sugestão: realizar a limpeza automaticamente no backend, removendo os caracteres especiais antes da validação.
    // Dessa forma, o cliente poderá enviar o documento com ou sem formatação (ex: 123.456.789-00 ou 12345678900), simplificando a integração.

    // 2. Validação do documento
    // Após a limpeza, aplicar a validação para verifiar a autentidade básica do documentos, pode gerar regra personalizada (Rule) no Laravel para validar CPF e CNPJ.

    // 3 . Adicionar senhas fortes para prevenir ataques de força bruta no sistema.

    // 4 .É necessário adicionar validações mais coerentes no Request da aplicação, definindo os tipos de dados esperados (como string, int, etc). Isso evita que, por exemplo, um array seja enviado em um campo que espera um valor simples, prevenindo erros 500 no sistema causados por falhas de tipagem ou problemas ao persistir dados no banco.
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_document_number'    => 'required|regex:/[0-9]{11}/i',
            'user_name'               => 'required',
            'company_document_number' => 'required|regex:/[0-9]{14}/i',
            'company_name'            => 'required',
            'email'                   => 'required|email',
            'password'                => 'required',
        ];
    }
}
