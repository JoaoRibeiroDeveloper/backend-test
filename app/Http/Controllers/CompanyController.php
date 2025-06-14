<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\UseCases\Company\Show;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\DefaultResponse;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\Company\ShowResource;
use App\Http\Resources\Company\UpdateResource;
use App\Domains\Company\Update as UpdateDomain;
use App\Repositories\Company\Update as CompanyUpdate;

class CompanyController extends Controller
{
    /**
     * Endpoint de dados de empresa
     *
     * GET api/company
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $response = (new Show(Auth::user()->company_id))->handle();

        return $this->response(
            new DefaultResponse(
                new ShowResource($response)
            )
        );
    }

    /**
     * Endpoint de modificação de empresa
     *
     * PATCH api/company
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        //O controller está chamando diretamente o domínio da aplicação, mas o ideal é que ele invoque um Use Case responsável por orquestrar a lógica específica do caso de uso. Isso melhora a separação de responsabilidades, facilita a manutenção e garante que a regra de negócio esteja isolada da camada de controle.
        $dominio = (new UpdateDomain(
            Auth::user()->company_id,
            $request->name,
        ))->handle();
        (new CompanyUpdate($dominio))->handle();


        //Aqui há um problema grave de segurança: o uso do first() após uma busca com find() pode retornar a primeira empresa do banco, mesmo que ela não corresponda ao UUID fornecido. Isso pode resultar no retorno de dados de uma empresa incorreta, expondo informações confidenciais indevidamente."
        $resposta = Company::find(Auth::user()->company_id)->toArray();

        return $this->response(
            new DefaultResponse(
                new UpdateResource($resposta)
            )
        );
    }
}
