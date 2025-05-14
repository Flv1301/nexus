<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CadastroCivilApiResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="CadastroCivil",
     *     type="object",
     *     title="Cadastro Civil API Resource",
     *     @OA\Property(property="rg", type="string", example="123456789"),
     *     @OA\Property(property="nome_completo", type="string", example="JOÃO DA SILVA"),
     *     @OA\Property(property="nome_social", type="string", example="JOÃO DA SILVA"),
     *     @OA\Property(property="data_nascimento", type="string", format="date", example="1980-01-01"),
     *     @OA\Property(property="cpf", type="string", example="12345678900"),
     *     @OA\Property(property="mae", type="string", example="MARIA DA SILVA"),
     * )
     */
    public function toArray($request): array
    {
        return [
            "rg" => $this->reg_geral_numerico,
            "nome_completo" => $this->nome_completo,
            "nome_social" => $this->nome_social,
            "data_nascimento" => $this->data_nascimento,
            "cpf" => $this->cpf,
            "mae" => $this->mae,
        ];
    }
}
