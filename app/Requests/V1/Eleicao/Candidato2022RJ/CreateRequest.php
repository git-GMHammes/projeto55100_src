<?php

namespace App\Requests\V1\Eleicao\Candidato2022RJ;

/**
 * Regras de validação para POST /create (tabela candidato_2022_RJ).
 *
 * DDL de referência:
 *   id                  VARCHAR(50)  NOT NULL  PK (não auto-increment)
 *   CD_MUNICIPIO        VARCHAR(10)  DEFAULT NULL
 *   CD_CARGO            VARCHAR(5)   DEFAULT NULL
 *   DS_CARGO            VARCHAR(50)  DEFAULT NULL
 *   NM_MUNICIPIO        VARCHAR(100) DEFAULT NULL
 *   NR_CANDIDATO        VARCHAR(10)  DEFAULT NULL
 *   NM_CANDIDATO        VARCHAR(150) DEFAULT NULL
 *   NM_SOCIAL_CANDIDATO VARCHAR(150) DEFAULT NULL
 *   SG_PARTIDO          VARCHAR(20)  DEFAULT NULL
 *   NM_PARTIDO          VARCHAR(100) DEFAULT NULL
 *   QT_VOTOS_NOMINAIS   BIGINT UNSIGNED DEFAULT NULL
 *   DS_SIT_TOT_TURNO    VARCHAR(50)  DEFAULT NULL
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'id'                  => 'required|string|max_length[50]',
            'CD_MUNICIPIO'        => 'permit_empty|string|max_length[10]',
            'CD_CARGO'            => 'permit_empty|string|max_length[5]',
            'DS_CARGO'            => 'permit_empty|string|max_length[50]',
            'NM_MUNICIPIO'        => 'permit_empty|string|max_length[100]',
            'NR_CANDIDATO'        => 'permit_empty|string|max_length[10]',
            'NM_CANDIDATO'        => 'permit_empty|string|max_length[150]',
            'NM_SOCIAL_CANDIDATO' => 'permit_empty|string|max_length[150]',
            'SG_PARTIDO'          => 'permit_empty|string|max_length[20]',
            'NM_PARTIDO'          => 'permit_empty|string|max_length[100]',
            'QT_VOTOS_NOMINAIS'   => 'permit_empty|is_natural',
            'DS_SIT_TOT_TURNO'    => 'permit_empty|string|max_length[50]',
        ];
    }

    public function messages(): array
    {
        return [
            'id' => [
                'required'   => 'O campo id é obrigatório',
                'max_length' => 'O id não pode exceder 50 caracteres',
            ],
            'CD_MUNICIPIO' => [
                'max_length' => 'CD_MUNICIPIO não pode exceder 10 caracteres',
            ],
            'CD_CARGO' => [
                'max_length' => 'CD_CARGO não pode exceder 5 caracteres',
            ],
            'DS_CARGO' => [
                'max_length' => 'DS_CARGO não pode exceder 50 caracteres',
            ],
            'NM_MUNICIPIO' => [
                'max_length' => 'NM_MUNICIPIO não pode exceder 100 caracteres',
            ],
            'NR_CANDIDATO' => [
                'max_length' => 'NR_CANDIDATO não pode exceder 10 caracteres',
            ],
            'NM_CANDIDATO' => [
                'max_length' => 'NM_CANDIDATO não pode exceder 150 caracteres',
            ],
            'NM_SOCIAL_CANDIDATO' => [
                'max_length' => 'NM_SOCIAL_CANDIDATO não pode exceder 150 caracteres',
            ],
            'SG_PARTIDO' => [
                'max_length' => 'SG_PARTIDO não pode exceder 20 caracteres',
            ],
            'NM_PARTIDO' => [
                'max_length' => 'NM_PARTIDO não pode exceder 100 caracteres',
            ],
            'QT_VOTOS_NOMINAIS' => [
                'is_natural' => 'QT_VOTOS_NOMINAIS deve ser um número inteiro não negativo',
            ],
            'DS_SIT_TOT_TURNO' => [
                'max_length' => 'DS_SIT_TOT_TURNO não pode exceder 50 caracteres',
            ],
        ];
    }
}
