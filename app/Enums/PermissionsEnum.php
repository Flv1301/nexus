<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 13/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Enums;

enum PermissionsEnum: string
{
    /**
     * MENU PRINCIPAL
     */
    case MENU_REGISTER = 'cadastro';
    case MENU_SEARCH = 'pesquisa';
    case MENU_SEARCH_ADVANCED = 'pesquisa_avancada';
    case MENU_SEARCH_PERSON_COMPLET = 'pesquisa_pessoa_completa';
    case MENU_SEARCH_VEHICLE = 'pesquisa_veiculo';
    case MENU_SEARCH_PIX = 'pesquisa_pix';
    case MENU_TOOL = 'ferramenta';
    case MENU_LOGS = 'log';
    case MENU_SYSTEM = 'sistema';
    case MENU_NOTIFICATION = 'notificacao';
    case MENU_GI2 = 'gi2';
    case MENU_IMEI = 'imei';
    case MENU_VCARD = 'vcard';
    case MENU_TELEPHONE = 'telefone';
    case MENU_VEHICLE = 'veiculo';
    case MENU_TICKET = 'bilhetagem';
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

    /** BANCOS DE CONSULTAS */
    case SEAP = 'seap';
    case SEAP_VISITANTE = 'seap_visitante';
    case SEAP_MONITOR_STUCK = 'seap_monitoramento';
    case SISP = 'sisp';
    case SISP_CONFIDENTIAL = 'sisp_sigiloso';
    case SRH = 'srh';
    case CORTEX = 'cortex';
    case GALTON = 'galton';
    case DPA = 'dpa';
    case POLINTER = 'polinter';
    case PRODEPA = 'prodepa';
    case SEDUC = 'seduc';
    case EQUATORIAL = 'equatorial';
    case CACADOR = 'cacador';
    case HYDRA = 'hydra';

    /** SUPORTE */
    case SUPPORT_RESPONSE = 'suporte resposta';
    case SUPPORT = 'suporte';

}
