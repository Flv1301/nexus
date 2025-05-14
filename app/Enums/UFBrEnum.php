<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 08/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Enums;

enum UFBrEnum: string
{
    case AC = 'AC';
    case AL = 'AL';
    case AP = 'AP';
    case AM = 'AM';
    case BA = 'BA';
    case CE = 'CE';
    case DF = 'DF';
    case ES = 'ES';
    case GO = 'GO';
    case MA = 'MA';
    case MT = 'MT';
    case MS = 'MS';
    case MG = 'MG';
    case PA = 'PA';
    case PB = 'PB';
    case PR = 'PR';
    case PE = 'PE';
    case PI = 'PI';
    case RJ = 'RJ';
    case RN = 'RN';
    case RS = 'RS';
    case RO = 'RO';
    case RR = 'RR';
    case SC = 'SC';
    case SP = 'SP';
    case SE = 'SE';
    case TO = 'TO';

    /**
     * @return string
     */
    public function state(): string
    {
        return match ($this) {
            UFBrEnum::AC => 'Acre',
            UFBrEnum::AL => 'Alagoas',
            UFBrEnum::AP => 'Amapá',
            UFBrEnum::AM => 'Amazonas',
            UFBrEnum::BA => 'Bahia',
            UFBrEnum::CE => 'Ceará',
            UFBrEnum::DF => 'Distrito Federal',
            UFBrEnum::ES => 'Espírito Santo',
            UFBrEnum::GO => 'Goiás',
            UFBrEnum::MA => 'Maranhão',
            UFBrEnum::MT => 'Mato Grosso',
            UFBrEnum::MS => 'Mato Grosso do Sul',
            UFBrEnum::MG => 'Minas Gerais',
            UFBrEnum::PA => 'Pará',
            UFBrEnum::PB => 'Paraíba',
            UFBrEnum::PR => 'Paraná',
            UFBrEnum::PE => 'Pernambuco',
            UFBrEnum::PI => 'Piauí',
            UFBrEnum::RJ => 'Rio de Janeiro',
            UFBrEnum::RN => 'Rio Grande do Norte',
            UFBrEnum::RS => 'Rio Grande do Sul',
            UFBrEnum::RO => 'Rondônia',
            UFBrEnum::RR => 'Roraima',
            UFBrEnum::SC => 'Santa Catarina',
            UFBrEnum::SP => 'São Paulo',
            UFBrEnum::SE => 'Sergipe',
            UFBrEnum::TO => 'Tocantins',
        };
    }
}
