<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\CadastroCivilApiResource;
use App\Services\Api\CadCivilProdepaService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CadCivilProdepaApiController extends Controller
{
    public function __construct(protected CadCivilProdepaService $service)
    {
    }

    /**
     * @OA\Get(
     *     path="/v1/cadastroscivis",
     *     summary="Pesquisar registros civis",
     *     tags={"Consulta de Registros Civis"},
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Nome do indivíduo",
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
     *         name="rg",
     *         in="query",
     *         description="Número do RG",
     *         required=false,
     *         @OA\Schema(type="string", example="123456789")
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
     *         description="Lista de registros civis encontrados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CadastroCivil"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum registro encontrado"
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

        if ($request->filled('rg')) {
            $filters['rg_geral_numerico'] = $request->input('rg');
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

        return CadastroCivilApiResource::collection($result);
    }

    /**
     * @OA\Get(
     *     path="/v1/cadastrocivil/{id}",
     *     summary="Obter detalhes de um registro civil",
     *     tags={"Consulta de Registros Civis"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do registro civil",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do registro civil",
     *         @OA\JsonContent(ref="#/components/schemas/CadastroCivil")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Registro não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function show($id): CadastroCivilApiResource
    {
        $result = $this->service->find($id);
        return CadastroCivilApiResource::make($result);
    }
}
