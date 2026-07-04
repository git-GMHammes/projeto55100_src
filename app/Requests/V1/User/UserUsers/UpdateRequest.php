<?php

namespace App\Requests\V1\User\UserUsers;

/**
 * Regras de validação para PUT /update/{id} (tabela user_users).
 *
 * password_hash não é mutável por este endpoint —
 * a troca de senha é tratada por fluxo dedicado.
 */
class UpdateRequest
{
    public function rules(): array
    {
        return [
            'profile_id'    => 'permit_empty|is_natural_no_zero',
            'username'      => 'permit_empty|string|max_length[80]',
            'email'         => 'permit_empty|string|max_length[191]',
            'status'        => 'permit_empty|in_list[active,inactive,blocked]',
            'last_login_at' => 'permit_empty|string',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_id' => [
                'is_natural_no_zero' => 'O campo profile_id deve ser um inteiro positivo',
            ],
            'username' => [
                'max_length' => 'O campo username não pode exceder 80 caracteres',
            ],
            'email' => [
                'max_length' => 'O campo email não pode exceder 191 caracteres',
            ],
            'status' => [
                'in_list' => 'O campo status deve ser active, inactive ou blocked',
            ],
        ];
    }
}
