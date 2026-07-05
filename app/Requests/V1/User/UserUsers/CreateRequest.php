<?php

namespace App\Requests\V1\User\UserUsers;

/**
 * Regras de validação para POST /create (tabela user_users).
 *
 * DDL de referência:
 *   profile_id    INT UNSIGNED                    NULL
 *   username      VARCHAR(80)                     NOT NULL UNIQUE
 *   email         VARCHAR(191)                    NOT NULL UNIQUE
 *   password_hash VARCHAR(255)                    NOT NULL
 *   status        ENUM('active','inactive','blocked') NOT NULL DEFAULT 'inactive'
 *   last_login_at DATETIME                        NULL
 *
 * status não é aceito como entrada — todo novo usuário nasce com o valor
 * DEFAULT da coluna no banco (ver Services\V1\User\UserUsers\Processor::prepareData).
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'profile_id'    => 'permit_empty|is_natural_no_zero',
            'username'      => 'required|string|max_length[80]',
            'email'         => 'required|string|max_length[191]',
            'password_hash' => 'required|string|max_length[255]',
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
                'required'   => 'O campo username é obrigatório',
                'max_length' => 'O campo username não pode exceder 80 caracteres',
            ],
            'email' => [
                'required'   => 'O campo email é obrigatório',
                'max_length' => 'O campo email não pode exceder 191 caracteres',
            ],
            'password_hash' => [
                'required'   => 'O campo password_hash é obrigatório',
                'max_length' => 'O campo password_hash não pode exceder 255 caracteres',
            ],
        ];
    }
}
