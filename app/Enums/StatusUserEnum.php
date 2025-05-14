<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

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
