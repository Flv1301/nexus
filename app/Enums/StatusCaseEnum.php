<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Enums;

enum StatusCaseEnum: string
{
    case ANALISE = 'ANALISE';
    case ATRASADO = 'ATRASADO';
    case AGUARDANDO = 'AGUARDANDO';
    case CONCLUIDO = 'CONCLUIDO';
    case CANCELADO = 'CANCELADO';
    case INICIADO = 'INICIADO';
    case PAUSADO = 'PAUSADO';
    case PENDENTE = 'PENDENTE';
    case SOLICITADO = 'SOLICITADO';
    case SUSPENSO = 'SUSPENSO';

    /**
     * @return string
     */
    public function style(): string
    {
        return match ($this) {
            StatusCaseEnum::ATRASADO => 'badge badge-danger',
            StatusCaseEnum::AGUARDANDO => 'badge badge-secondary',
            StatusCaseEnum::ANALISE => 'badge badge-info',
            StatusCaseEnum::CANCELADO => 'badge badge-gray',
            StatusCaseEnum::CONCLUIDO => 'badge badge-success',
            StatusCaseEnum::INICIADO => 'badge badge-primary',
            StatusCaseEnum::PAUSADO => 'badge badge-black',
            StatusCaseEnum::PENDENTE => 'badge badge-light',
            StatusCaseEnum::SOLICITADO => 'badge badge-blue',
            StatusCaseEnum::SUSPENSO => 'badge badge-warning',
        };
    }
}
