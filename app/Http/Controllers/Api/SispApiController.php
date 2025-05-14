<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\SispBopApiResource;
use App\Http\Resources\SispEnvolvidosApiResource;
use App\Services\Api\SispService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SispApiController extends Controller
{
    public function __construct(protected SispService $service)
    {
    }

    /**
     * @OA\Get(
     *     path="/v1/sisp",
     *     summary="Pesquisar envolvidos no SISP",
     *     tags={"Consulta de Ocorrências SISP"},
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Nome do envolvido",
     *         required=false,
     *         @OA\Schema(type="string", example="JOAO DA SILVA")
     *     ),
     *     @OA\Parameter(
     *         name="data_nascimento",
     *         in="query",
     *         description="Data de nascimento no formato YYYY-MM-DD",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="1980-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         description="Número do CPF",
     *         required=false,
     *         @OA\Schema(type="string", example="12345678900")
     *     ),
     *     @OA\Parameter(
     *         name="mae",
     *         in="query",
     *         description="Nome da mãe",
     *         required=false,
     *         @OA\Schema(type="string", example="MARIA DA SILVA")
     *     ),
     *     @OA\Parameter(
     *         name="pai",
     *         in="query",
     *         description="Nome do pai",
     *         required=false,
     *         @OA\Schema(type="string", example="JOAO DA SILVA")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de envolvidos encontrados no SISP",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SispEnvolvido"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum envolvido encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $filters = $request->all();

        Str::asciiRequest($request, $filters);
        Str::upperRequest($request, $filters);

        if ($request->filled('nome')) {
            $filters['nome'] = $request->input('nome');
        }

        if ($request->filled('data_nascimento')) {
            $filters['data_nascimento'] = $request->input('data_nascimento');
        }

        if ($request->filled('cpf')) {
            $filters['cpf'] = $request->input('cpf');
        }

        if ($request->filled('mae')) {
            $filters['mae'] = $request->input('mae');
        }

        if ($request->filled('pai')) {
            $filters['pai'] = $request->input('pai');
        }

        $result = $this->service->paginate($filters);

        return SispEnvolvidosApiResource::collection($result);
    }

    /**
     * @OA\Get(
     *     path="/v1/sisp/{id}",
     *     summary="Obter detalhes de um BOP no SISP",
     *     tags={"Consulta de Ocorrências SISP"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do BOP no SISP",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do BOP no SISP",
     *         @OA\JsonContent(ref="#/components/schemas/SispBop")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="BOP não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function show($id): SispBopApiResource
    {
        $result = $this->service->find($id);
        return SispBopApiResource::make($result);
    }
}
