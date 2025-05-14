<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Enums;

enum TypeEmailNotificationsEnum: string
{
    case REGISTRATION_RELEASE = 'LIBERACAO DE CADASTRO';
    case DOCUMENT_RELEASE = 'LIBERACAO DE DOCUMENTO';
    case ACCESS_RELEASE = 'LIBERACAO DE ACESSO';
    case NOTICE = 'AVISO';
    case NOTIFICATIONS = 'NOTIFICACAO';
}
