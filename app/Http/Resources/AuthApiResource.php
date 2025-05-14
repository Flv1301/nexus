<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthApiResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="AuthApiResource",
     *     type="object",
     *     title="Autenticação API Resource",
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com")
     * )
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
