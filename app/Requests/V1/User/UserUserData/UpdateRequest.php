<?php

namespace App\Requests\V1\User\UserUserData;

/**
 * Regras de validação para PUT /update/{id} (tabela user_user_data).
 *
 * user_id é imutável após o create (chave de vínculo com user_users).
 */
class UpdateRequest
{
    public function rules(): array
    {
        return [
            'full_name'             => 'permit_empty|string|max_length[191]',
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
            'full_name' => [
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
