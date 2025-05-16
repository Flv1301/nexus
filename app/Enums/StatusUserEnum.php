<?php

namespace App\Enums;

enum StatusUserEnum
{
    case ATIVO;
    case INATIVO;
    case SUSPENSO;
    case CANCELADO;

    /**
     * @return string
     */
    public function style(): string
    {
        return match ($this) {
            StatusUserEnum::ATIVO => 'badge badge-success',
            StatusUserEnum::INATIVO => 'badge badge-gray',
            StatusUserEnum::SUSPENSO => 'badge badge-warning',
            StatusUserEnum::CANCELADO => 'badge badge-danger'
        };
    }
}
