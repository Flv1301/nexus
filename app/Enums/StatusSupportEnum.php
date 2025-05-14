<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Enums;

enum StatusSupportEnum: string
{
    case ABERTO = 'Aberto';
    case FECHADO = 'Fechado';
    case DESENVOLVIMENTO = 'Desenvolvimento';
    case MANUTENCAO = 'Manutencao';
    case REJEITADO = 'Rejeitado';
    case ANALISE = 'Analise';

    /**
     * @return string
     */
    public function style(): string
    {
        return match ($this) {
            StatusSupportEnum::ABERTO => 'badge badge-success',
            StatusSupportEnum::FECHADO => 'badge badge-secondary',
            StatusSupportEnum::DESENVOLVIMENTO => 'badge badge-warning',
            StatusSupportEnum::MANUTENCAO => 'badge badge-info',
            StatusSupportEnum::REJEITADO => 'badge badge-danger',
            StatusSupportEnum::ANALISE => 'badge badge-primary',
        };
    }
}
