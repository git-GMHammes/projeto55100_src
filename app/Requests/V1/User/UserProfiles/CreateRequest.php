<?php

namespace App\Requests\V1\User\UserProfiles;

/**
 * Regras de validação para POST /create (tabela user_profiles).
 *
 * DDL de referência:
 *   name        VARCHAR(100) NOT NULL
 *   slug        VARCHAR(100) NOT NULL UNIQUE
 *   description VARCHAR(255) NULL
 *   permissions JSON         NULL
 *   status      TINYINT(1)   NOT NULL DEFAULT 1
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max_length[100]',
            'slug'        => 'required|string|max_length[100]',
            'description' => 'permit_empty|string|max_length[255]',
            'permissions' => 'permit_empty|string',
            'status'      => 'permit_empty|in_list[0,1]',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => [
                'required'   => 'O campo name é obrigatório',
                'max_length' => 'O campo name não pode exceder 100 caracteres',
            ],
            'slug' => [
                'required'   => 'O campo slug é obrigatório',
                'max_length' => 'O campo slug não pode exceder 100 caracteres',
            ],
            'description' => [
                'max_length' => 'O campo description não pode exceder 255 caracteres',
            ],
            'status' => [
                'in_list' => 'O campo status deve ser 0 ou 1',
            ],
        ];
    }
}
