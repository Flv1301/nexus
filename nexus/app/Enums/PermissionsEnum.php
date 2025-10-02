<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    /**
     * MENU PRINCIPAL
     */
    case MENU_REGISTER = 'cadastro';
    case MENU_SEARCH = 'pesquisa';
    case MENU_SEARCH_PERSON_COMPLET = 'pesquisa_pessoa_completa';
    case MENU_SEARCH_VEHICLE = 'pesquisa_veiculo';
    case MENU_SEARCH_PIX = 'pesquisa_pix';
    case MENU_TOOL = 'ferramenta';
    case MENU_LOGS = 'log';
    case MENU_SYSTEM = 'sistema';
    case MENU_NOTIFICATION = 'notificacao';
    case MENU_VCARD = 'vcard';
    case MENU_TELEPHONE = 'telefone';
    case MENU_VEHICLE = 'veiculo';
    case MENU_LETTER = 'oficio';


    /** CADASTROS */
    case USER = 'usuario';
    case USER_READ = 'usuario.ler';
    case USER_WRITE = 'usuario.cadastrar';
    case USER_DELETE = 'usuario.excluir';
    case USER_UPDATE = 'usuario.atualizar';

    case PERSON = 'pessoa';
    case PERSON_READ = 'pessoa.ler';
    case PERSON_WRITE = 'pessoa.cadastrar';
    case PERSON_DELETE = 'pessoa.excluir';
    case PERSON_UPDATE = 'pessoa.atualizar';
    case PERSON_SEARCH = 'pessoa.pesquisar';
    case SISFAC = 'sisfac';

    case CASE = 'caso';
    case CASE_READ = 'caso.ler';
    case CASE_WRITE = 'caso.cadastrar';
    case CASE_DELETE = 'caso.excluir';
    case CASE_UPDATE = 'caso.atualizar';
    case CASE_ATTACHMENT = 'caso.anexar';

    case PERMISSION = 'permissao';
    case PERMISSION_READ = 'permissao.ler';
    case PERMISSION_WRITE = 'permissao.cadastrar';
    case PERMISSION_DELETE = 'permissao.excluir';
    case PERMISSION_UPDATE = 'permissao.atualizar';

    case ROLE = 'funcao';
    case ROLE_READ = 'funcao.ler';
    case ROLE_WRITE = 'funcao.cadastrar';
    case ROLE_DELETE = 'funcao.excluir';
    case ROLE_UPDATE = 'funcao.atualizar';

    case UNITY = 'unidade';
    case UNITY_READ = 'unidade.ler';
    case UNITY_WRITE = 'unidade.cadastrar';
    case UNITY_DELETE = 'unidade.excluir';
    case UNITY_UPDATE = 'unidade.atualizar';
    case UNITY_SECTOR_SEARCH = 'unidade.setor.pesquisar';

    case SECTOR = 'setor';
    case SECTOR_READ = 'setor.ler';
    case SECTOR_WRITE = 'setor.cadastrar';
    case SECTOR_DELETE = 'setor.excluir';
    case SECTOR_UPDATE = 'setor.atualizar';
    case SECTOR_USER_SEARCH = 'setor.usuario.pesquisar';

    case PROCEDURE = 'tramitacao';

    case WHATSAPP = 'whatsapp';
    case WHATSAPP_READ = 'whatsapp.ler';
    case WHATSAPP_WRITE = 'whatsapp.cadastrar';
    case WHATSAPP_DELETE = 'whatsapp.excluir';
    case WHATSAPP_UPDATE = 'whatsapp.atualizar';

    /** BANCOS DE CONSULTAS */
   case CORTEX = 'cortex';
    case NEXUS = 'nexus';

}
