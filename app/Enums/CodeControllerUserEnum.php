<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Enums;

enum CodeControllerUserEnum
{
    case CADASTRADO;
    case PENDENTE;
    case CONCLUIDO;
    case CANCELADO;

    /**
     * @return string
     */
    public function style(): string
    {
        return match ($this) {
            CodeControllerUserEnum::CADASTRADO => 'badge badge-info',
            CodeControllerUserEnum::PENDENTE => 'badge badge-warning',
            CodeControllerUserEnum::CONCLUIDO => 'badge badge-success',
            CodeControllerUserEnum::CANCELADO => 'badge badge-danger'
        };
    }
}
