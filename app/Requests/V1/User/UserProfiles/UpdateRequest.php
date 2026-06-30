<?php

namespace App\Requests\V1\User\UserProfiles;

/**
 * Regras de validação para PUT /update/{id} (tabela user_profiles).
 */
class UpdateRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'permit_empty|string|max_length[100]',
            'slug'        => 'permit_empty|string|max_length[100]',
            'description' => 'permit_empty|string|max_length[255]',
            'permissions' => 'permit_empty|string',
            'status'      => 'permit_empty|in_list[0,1]',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => [
                'max_length' => 'O campo name não pode exceder 100 caracteres',
            ],
            'slug' => [
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
