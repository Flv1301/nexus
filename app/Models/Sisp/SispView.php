<?php

namespace App\Models\Sisp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SispView extends Model
{
    protected $connection = "sisp";
    protected $table = "sisp_view";

    public function __construct()
    {

    }


    public static function getNameFields()
    {
        return DB::connection("sisp")->select("SELECT    attname
FROM pg_class c
INNER JOIN pg_attribute a ON attrelid = oid
INNER JOIN pg_type t ON oid = atttypid
WHERE relname = 'sisp_view' and attname <> 'xmax' and attname <> 'cmax' and attname <> 'cmin' and attname <> 'tableoid' and attname <> 'xmin' and attname <> 'ctid'");

    }

    public static function getFieldsNames()
    {
        $fields = self::getNameAndHintSisp();
        $options = '';
        foreach ($fields as $key => $value) {

            $options .= '<option value="' . $key . '">' . $value['alias'] . '</option>';
        }
        echo html_entity_decode($options);
    }

    public static function getSisp($array)
    {
        $sisp = SispView::query()->where($array)->get();
        //$presos = DB::connection("pgsql2")->query()->where($array);
        return $sisp;
    }

    public static function getNameAndHintSisp()
    {
        $helpers = array(
            'sisp' => array('alias' => 'SISP', 'placeholder' => 'SISP 1 ou SISP 2'),
            'n_bop' => array('alias' => 'Numero do BOP', 'placeholder' => 'EX: 00099/2020.100683-5'),
            'unidade_responsavel' => array('alias' => 'Unidade Responsável', 'placeholder' => 'EX: MOJU - DELEGACIA DE POLICIA - 4ª RISP'),
            'registros' => array('alias' => 'Registro', 'placeholder' => 'EX: FURTO, ROUBO, LESÃO CORPORAL DOLOSA'),
            'dt_registro' => array('alias' => 'Data do Registro', 'placeholder' => 'EX: 2020-06-13 08:39:13'),
            'data_fato' => array('alias' => 'Data do Fato', 'placeholder' => '"12/06/2020"'),
            'natureza' => array('alias' => 'Natureza', 'placeholder' => 'EX: FURTO SIMPLES CAPUT'),
            'ds_bairro_fato' => array('alias' => 'Bairro do Fato', 'placeholder' => 'EX: CIDADE NOVA'),
            'meio_empregado' => array('alias' => 'Meio Empregado', 'placeholder' => 'EX: ARMA DE FOGO, OUTROS MEIOS'),
            'localgp_ocorrencia' => array('alias' => 'Tipo de Local', 'placeholder' => 'EX: VIA PÚBLICA, ESTABELECIMENTO BANCÁRIO'),
            'localidade_fato' => array('alias' => 'Cidade do Fato', 'placeholder' => 'EX: MARABA, BELEM'),
            'nm_envolvido' => array('alias' => 'Nome do Envolvido', 'placeholder' => 'NOME DO ENVOLVIDO NO BOP'),
            'sexo' => array('alias' => 'Sexo do Envolvido', 'placeholder' => 'MASCULINO OU FEMININO'),
            'estado_civil' => array('alias' => 'Estado Civil do Envolvido', 'placeholder' => 'EX: SOLTEIRO, DIVORCIADO, UNIÃO ESTÁVEL'),
            'mae' => array('alias' => 'Mãe do Envolvido', 'placeholder' => 'EX: NOME DA MÃE DO ENVOLVIDO NO BOP'),
            'pai' => array('alias' => 'Pai do Envolvido', 'placeholder' => 'EX: NOME DO PAI DO ENVOLVIDO NO BOP'),
            'naturalidade' => array('alias' => 'Naturalidade do Envolvido', 'placeholder' => 'EX: BELÉM'),
            'nacionalidade' => array('alias' => 'Nacionalidade do Envolvido', 'placeholder' => 'EX: BRASIL'),
            'profissao' => array('alias' => 'Profissão do Envolvido', 'placeholder' => 'EX: POLICIAL MILITAR'),
            'cpf' => array('alias' => 'CPF do Envolvido', 'placeholder' => 'CPF SEM PONTOS. EX: 12345678910'),
            'contato' => array('alias' => 'Contato do Envolvido', 'placeholder' => 'EX: 91 987654321'),
            'localidade' => array('alias' => 'Cidade do Envolvido', 'placeholder' => 'EX: BELÉM'),
            'bairro' => array('alias' => 'Bairro do Envolvido', 'placeholder' => 'EX: CREMAÇÃO'),
            'endereco' => array('alias' => 'Rua do Envolvido', 'placeholder' => 'EX: DOS MUNDURUCUS'),
            'uf' => array('alias' => 'Estado do Envolvido', 'placeholder' => 'EX: PA'),
            'cep' => array('alias' => 'CEP do Envolvido', 'placeholder' => 'EX: 68450000'),
            'ds_atuacao' => array('alias' => 'Atuação', 'placeholder' => 'VÍTIMA, RELATOR, SUSPEITO, TESTEMUNHA'),
            'relato' => array('alias' => 'Relato', 'placeholder' => 'PESQUISAR NO CORPO DA OCORRÊNCIA')

        );
        return $helpers;
    }

}
