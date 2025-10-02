<?php

namespace App\Enums;

enum TypeEmailNotificationsEnum: string
{
    case REGISTRATION_RELEASE = 'LIBERACAO DE CADASTRO';
    case DOCUMENT_RELEASE = 'LIBERACAO DE DOCUMENTO';
    case ACCESS_RELEASE = 'LIBERACAO DE ACESSO';
    case NOTICE = 'AVISO';
    case NOTIFICATIONS = 'NOTIFICACAO';
}
