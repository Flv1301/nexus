<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 21/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

return [
    'file_type_layout' => [
        'document' => 'App\FileLayouts\Document\DocumentLayout',
        'whats_ticket' => 'App\FileLayouts\Whatsapp\WhatsappHtmlTicketLayout',
        'whats_access_log' => 'App\FileLayouts\Whatsapp\WhatsappHtmlAccessLogLayout',
        'facebook_access_log' => 'App\FileLayouts\Facebook\FacebookHtmlAccessLogLayout',
        'instagram_access_log' => 'App\FileLayouts\Instagram\InstagramHtmlAccessLogLayout',
    ],
    'file_type' => [
        'document' => 'App\Models\Files\Document',
        'whats_ticket' => 'App\Models\Files\Whatsapp\Whatsapp',
        'whats_access_log' => 'App\Models\Files\Whatsapp\Whatsapp',
        'facebook_access_log' => 'App\Models\Files\Facebook\Facebook',
        'instagram_access_log' => 'App\Models\Files\Facebook\Facebook',
    ],
    'file_alias' => [
        'document' => 'document',
        'whats_ticket' => 'whatsapp_ticket',
        'whats_access_log' => 'whatsapp_log',
        'facebook_access_log' => 'facebook_log',
        'instagram_access_log' => 'instagram_log',
    ],
    'file_alias_pt_BR' => [
        'document' => 'Arquivo',
        'whatsapp_ticket' => 'Bilhetagem Whatsapp',
        'whatsapp_log' => 'Log Acesso Whatsapp',
        'facebook_log' => 'Log Acesso Facebook',
        'instagram_log' => 'Log Acesso Instagram',
    ],
    'mimes' => [
        'whats_ticket' => 'hmtl',
        'whats_access_log' => 'html',
        'facebook_access_log' => 'html',
        'document' => 'doc, docx, xls, xlsx, csv, txt, pdf, jpg, jpge, png, html, odt, ods, xml'
    ],
    'except_name' => [
        'html'
    ],
    'file_path' => 'case/',
];
