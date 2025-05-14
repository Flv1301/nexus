<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SispEnvolvidosApiResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="SispEnvolvido",
     *     type="object",
     *     title="Sisp Envolvidos API Resource",
     *     @OA\Property(property="bop_id", type="integer", example=12345),
     *     @OA\Property(property="nome", type="string", example="João da Silva"),
     *     @OA\Property(property="nascimento", type="string", format="date", example="1980-01-01"),
     *     @OA\Property(property="mae", type="string", example="Maria da Silva"),
     *     @OA\Property(property="cpf", type="string", example="12345678900"),
     * )
     */
    public function toArray($request): array
    {
        return [
            "bop_id" => $this->bopenv_bop_key,
            "nome" => $this->nm_envolvido,
            "nascimento" => $this->nascimento,
            "mae" => $this->mae,
            "cpf" => $this->cpf,
        ];
    }
}
