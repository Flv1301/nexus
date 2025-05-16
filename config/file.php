<?php

return [
    'file_type_layout' => [
        'document' => 'App\FileLayouts\Document\DocumentLayout',
    ],
    'file_type' => [
        'document' => 'App\Models\Files\Document',
    ],
    'file_alias' => [
        'document' => 'document',
    ],
    'file_alias_pt_BR' => [
        'document' => 'Arquivo',
    ],
    'mimes' => [
        'document' => 'doc, docx, xls, xlsx, csv, txt, pdf, jpg, jpge, png, html, odt, ods, xml'
    ],
    'except_name' => [
        'html'
    ],
    'file_path' => 'case/',
];
