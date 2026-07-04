<?php

namespace App\Requests\V1\User\UserUserData;

/**
 * Regras de validação para POST /create (tabela user_user_data).
 *
 * DDL de referência:
 *   user_id              INT UNSIGNED   NULL UNIQUE — FK user_users.id
 *   full_name            VARCHAR(191)   NOT NULL
 *   cpf                  VARBINARY(255) NULL — AES_ENCRYPT, nunca plain text
 *   birth_date           DATE           NULL
 *   gender               ENUM(M,F,NB,ND) NULL
 *   phone                VARCHAR(20)    NULL
 *   address_*            VARCHAR        NULL
 *   lgpd_consent_at      DATETIME       NULL
 *   lgpd_consent_ip      VARCHAR(45)    NULL
 *   data_retention_until DATE           NULL
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'user_id'               => 'permit_empty|is_natural_no_zero',
            'full_name'             => 'required|string|max_length[191]',
            'cpf'                   => 'permit_empty|string',
            'birth_date'            => 'permit_empty|string',
            'gender'                => 'permit_empty|in_list[M,F,NB,ND]',
            'phone'                 => 'permit_empty|string|max_length[20]',
            'address_street'        => 'permit_empty|string|max_length[191]',
            'address_number'        => 'permit_empty|string|max_length[20]',
            'address_complement'    => 'permit_empty|string|max_length[100]',
            'address_neighborhood'  => 'permit_empty|string|max_length[100]',
            'address_city'          => 'permit_empty|string|max_length[100]',
            'address_state'         => 'permit_empty|string|max_length[2]',
            'address_zipcode'       => 'permit_empty|string|max_length[10]',
            'lgpd_consent_at'       => 'permit_empty|string',
            'lgpd_consent_ip'       => 'permit_empty|string|max_length[45]',
            'data_retention_until'  => 'permit_empty|string',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id' => [
                'is_natural_no_zero' => 'O campo user_id deve ser um inteiro positivo',
            ],
            'full_name' => [
                'required'   => 'O campo full_name é obrigatório',
                'max_length' => 'O campo full_name não pode exceder 191 caracteres',
            ],
            'gender' => [
                'in_list' => 'O campo gender deve ser M, F, NB ou ND',
            ],
            'phone' => [
                'max_length' => 'O campo phone não pode exceder 20 caracteres',
            ],
            'address_state' => [
                'max_length' => 'O campo address_state deve ter no máximo 2 caracteres (UF)',
            ],
            'address_zipcode' => [
                'max_length' => 'O campo address_zipcode não pode exceder 10 caracteres',
            ],
            'lgpd_consent_ip' => [
                'max_length' => 'O campo lgpd_consent_ip não pode exceder 45 caracteres',
            ],
        ];
    }
}
