<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SispBopApiResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="SispBop",
     *     type="object",
     *     title="Sisp BOP API Resource",
     *     @OA\Property(property="bop_id", type="integer", example=12345),
     *     @OA\Property(property="bop", type="string", example="BOP123456789"),
     *     @OA\Property(property="unidade_responsavel", type="string", example="Delegacia XYZ"),
     *     @OA\Property(property="registros", type="string", example="Registro123"),
     *     @OA\Property(property="data_registro", type="string", format="date", example="2024-01-01"),
     *     @OA\Property(property="data_fato", type="string", format="date", example="2024-01-01"),
     *     @OA\Property(property="natureza", type="string", example="Roubo"),
     *     @OA\Property(property="bairro_fato", type="string", example="Centro"),
     *     @OA\Property(property="meio_empregado", type="string", example="Arma de fogo"),
     *     @OA\Property(property="local_ocorrencia", type="string", example="Rua A"),
     *     @OA\Property(property="localidade_fato", type="string", example="Cidade B"),
     *     @OA\Property(
     *         property="envolvidos",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/SispEnvolvido")
     *     ),
     *     @OA\Property(property="relato", type="string", example="Descrição do relato do ocorrido...")
     * )
     */
    public function toArray($request): array
    {
        $user = Auth::user();
        $hasPermission = $user->hasDirectPermission('sisp_sigiloso');

        $commonData = [
            "bop_id" => $this->bop_bop_key,
            "bop" => $this->n_bop,
            "unidade_responsavel" => $this->unidade_responsavel,
            "data_registro" => $this->dt_registro,
            "data_fato" => $this->dt_fato,
        ];

        if (!$hasPermission && $this->sigiloso) {
            return array_merge($commonData, [
                "registros" => '',
                "natureza" => '',
                "bairro_fato" => '',
                "meio_empregado" => '',
                "local_ocorrencia" => '',
                "localidade_fato" => '',
                "envolvidos" => '',
                "relato" => 'Marcado como sigiloso'
            ]);
        }

        return array_merge($commonData, [
            "registros" => $this->registros,
            "natureza" => $this->natureza,
            "bairro_fato" => $this->ds_bairro_fato,
            "meio_empregado" => $this->meio_empregado,
            "local_ocorrencia" => $this->localgp_ocorrencia,
            "localidade_fato" => $this->localidade_fato,
            "envolvidos" => SispEnvolvidosApiResource::collection($this->bopenv),
            "relato" => $this->boprel->relato
        ]);
    }
}
