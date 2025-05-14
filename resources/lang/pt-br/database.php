<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

return [
//    'sisp' => [
//        'n_bop' => ['alias' => 'Numero do BOP', 'placeholder' => 'EX: xxxxx/xxxx.xxxxxx-x'],
//        'unidade_responsavel' => [
//            'alias' => 'Unidade Responsável',
//            'placeholder' => 'Nome da Unidade'
//        ],
//        'registros' => ['alias' => 'Registro', 'placeholder' => 'EX: FURTO'],
//        'dt_registro' => ['alias' => 'Data do Registro', 'placeholder' => 'EX: xx/xx/xxxx 00:00:01'],
//        'dt_fato' => ['alias' => 'Data do Fato', 'placeholder' => 'xx/xx/xxxx'],
//        'natureza' => ['alias' => 'Natureza', 'placeholder' => 'EX: FURTO SIMPLES'],
//        'ds_bairro_fato' => ['alias' => 'Bairro do Fato', 'placeholder' => 'Bairro do Fato'],
//        'meio_empregado' => ['alias' => 'Meio Empregado', 'placeholder' => 'EX: ARMA DE FOGO'],
//        'localgp_ocorrencia' => [
//            'alias' => 'Tipo de Local',
//            'placeholder' => 'EX: VIA PÚBLICA'
//        ],
//        'localidade_fato' => ['alias' => 'Cidade do Fato', 'placeholder' => 'Cidade do Fato'],
//        'nm_envolvido' => ['alias' => 'Nome do Envolvido', 'placeholder' => 'Nome do Envolvido'],
//        'sexo' => ['alias' => 'Sexo do Envolvido', 'placeholder' => 'Ex: MASCULINO'],
//        'estado_civil' => [
//            'alias' => 'Estado Civil do Envolvido',
//            'placeholder' => 'EX: SOLTEIRO'
//        ],
//        'mae' => ['alias' => 'Mãe do Envolvido', 'placeholder' => 'Nome da Mãe do Envolvido'],
//        'pai' => ['alias' => 'Pai do Envolvido', 'placeholder' => 'Nome do Pai do Envolvido'],
//        'naturalidade' => ['alias' => 'Naturalidade do Envolvido', 'placeholder' => 'Naturalidade do Envolvido'],
//        'nacionalidade' => ['alias' => 'Nacionalidade do Envolvido', 'placeholder' => 'Nacionalidade do Envolvido'],
//        'profissao' => ['alias' => 'Profissão do Envolvido', 'placeholder' => 'Profissão do Envolvido'],
//        'cpf' => ['alias' => 'CPF do Envolvido', 'placeholder' => 'EX: 12345678910'],
//        'contato' => ['alias' => 'Contato do Envolvido', 'placeholder' => 'EX: xx xxxxxxxxx'],
//        'localidade' => ['alias' => 'Cidade do Envolvido', 'placeholder' => 'Cidade do Envolvido'],
//        'bairro' => ['alias' => 'Bairro do Envolvido', 'placeholder' => 'Bairro do Envolvido'],
//        'endereco' => ['alias' => 'Rua do Envolvido', 'placeholder' => 'Rua do Envolvido'],
//        'uf' => ['alias' => 'Estado do Envolvido', 'placeholder' => 'Estado do Envolvido'],
//        'cep' => ['alias' => 'CEP do Envolvido', 'placeholder' => 'EX: xxxxxxxx'],
//        'ds_atuacao' => ['alias' => 'Atuação', 'placeholder' => 'EX. Vitima'],
//        'relato' => ['alias' => 'Relato', 'placeholder' => 'PESQUISAR NO CORPO DA OCORRÊNCIA'],
//    ],
    'sisp' => [
        'bop' => ['alias' => 'Número do BOP', 'placeholder' => 'Ex.: xxxxx/xxxx.xxxxxx-x'],
        'unidade_responsavel' => ['alias' => 'Unidade Responsável', 'placeholder' => 'Ex.: REDENÇAO - DELEGACIA DE POLICIA - 13ª RISP'],
        'registros' => ['alias' => 'Registro Tipo', 'placeholder' => 'Ex.: FURTO'],
        'data_registro' => ['alias' => 'Data do Registro', 'placeholder' => 'EX.: xx/xx/xxxx'],
        'data_fato' => ['alias' => 'Data do Fato', 'placeholder' => 'Ex.: xx/xx/xxxx'],
        'natureza' => ['alias' => 'Natureza', 'placeholder' => 'EX.: DESACATO'],
        'bairro_fato' => ['alias' => 'Bairro do Fato', 'placeholder' => 'Ex.: CENTRO'],
        'meio_empregado' => ['alias' => 'Meio Empregado', 'placeholder' => 'EX.: ARMA DE FOGO'],
        'local_ocorrencia' => ['alias' => 'Tipo de Local', 'placeholder' => 'EX.: VIA PUBLICA'],
        'local_fato' => ['alias' => 'Cidade do Fato', 'placeholder' => 'Ex.: BELEM'],
        'nome' => ['alias' => 'Nome', 'placeholder' => 'Nome'],
        'sexo' => ['alias' => 'Sexo', 'placeholder' => 'Ex.: MASCULINO'],
        'mae' => ['alias' => 'Mãe', 'placeholder' => 'Nome da mãe'],
        'pai' => ['alias' => 'Pai', 'placeholder' => 'Nome do pai'],
        'naturalidade' => ['alias' => 'Naturalidade', 'placeholder' => 'Ex.: BELEM'],
        'profissao' => ['alias' => 'Profissão', 'placeholder' => 'Ex.: ADVOGADO'],
        'cpf' => ['alias' => 'CPF', 'placeholder' => 'EX: 12345678910'],
        'telefone' => ['alias' => 'Telefone', 'placeholder' => 'Apenas números.'],
        'atuacao' => ['alias' => 'Atuação', 'placeholder' => 'EX.: VITIMA'],
        'relato' => ['alias' => 'Relato', 'placeholder' => 'Relato da ocorrência.'],
    ],
    'seap' => [
        'preso_nome' => ['alias' => 'Nome', 'placeholder' => 'Nome do Preso'],
        'presoalcunha_descricao' => ['alias' => 'Alcunha', 'placeholder' => 'Alcunha'],
        'preso_sexo' => ['alias' => 'Sexo', 'placeholder' => 'Digite M ou F'],
        'preso_dataultimaprisao' => ['alias' => 'Data da Última Prisão', 'placeholder' => 'Ex. xx/xx/xxxx'],
        'preso_datanascimento' => ['alias' => 'Data de Nascimento', 'placeholder' => 'Ex. xx/xx/xxxx'],
        'preso_filiacaopai' => ['alias' => 'Nome do Pai', 'placeholder' => 'Digite o Nome do Pai'],
        'preso_filiacaomae' => ['alias' => 'Nome da Mãe', 'placeholder' => 'Digite o Nome da Mãe'],
        'enderecopreso_bairro' => ['alias' => 'Bairro', 'placeholder' => 'Digite o Nome do Bairro'],
        'municipio_nome' => ['alias' => 'Município', 'placeholder' => 'Digite o Nome do Município'],
        'enderecopreso_logradouro' => ['alias' => 'Logradouro', 'placeholder' => 'Digite o Nome do Logradouro'],
        'distrito_nome' => ['alias' => 'Distrito', 'placeholder' => 'Digite o Nome do Distrito'],
        'municipio_siglauf' => ['alias' => 'UF', 'placeholder' => 'Ex. PA'],
        'corpeleetnia_descricao' => ['alias' => 'Cor da Pele', 'placeholder' => 'EX. PARDA'],
        'telefone_numero' => ['alias' => 'Número de Telefone', 'placeholder' => 'Digite o Número de Telefone'],
        'altura_descricao' => ['alias' => 'Altura', 'placeholder' => 'EX. 1,65M'],
        'corolhos_descricao' => ['alias' => 'Cor dos Olhos', 'placeholder' => 'EX. CASTANHOS'],
        'corcabelo_descricao' => ['alias' => 'Cor do Cabelo', 'placeholder' => 'EX. PRETOS'],
        'tipocabelo_descricao' => ['alias' => 'Tipo do Cabelo', 'placeholder' => 'EX. ONDULADOS OU LISOS'],
        'situacaopreso_descricao' => ['alias' => 'Situação', 'placeholder' => 'Ex. PRESO OU EVADIDO'],
        'cpf' => ['alias' => 'CPF', 'placeholder' => 'Digite o Número do Documento'],
        'rg' => ['alias' => 'RG', 'placeholder' => 'Digite o Número do Documento'],
        'peculiaridade_descricao' => ['alias' => 'Peculiariedade', 'placeholder' => 'Ex. DRAGAO OU FRATURA'],
        'peculiaridade_orientacao' => ['alias' => 'Local da Peculiaridade', 'placeholder' => 'Ex. FRENTE OU COSTAS'],
        'peculiaridade_tipo' => ['alias' => 'Tipo da Peculiariedade', 'placeholder' => 'Ex. TATUAGENS ou CICATRIZES OU SINAIS OU MARCAS'],
    ],

    'hydra' => [],
];
