<?php

namespace App\Requests\V1\User\UserRefreshTokens;

/**
 * Regras de validação para PUT /update/{id} (tabela user_refresh_tokens).
 *
 * Refresh tokens geralmente não são alterados após a criação.
 */
class UpdateRequest
{
    public function rules(): array
    {
        return [
            'used_at'    => 'permit_empty|valid_date',
            'ip_address' => 'permit_empty|string|max_length[45]',
            'user_agent' => 'permit_empty|string|max_length[500]',
        ];
    }

    public function messages(): array
    {
        return [
            'used_at' => [
                'valid_date' => 'O campo used_at deve ser uma data válida',
            ],
            'ip_address' => [
                'max_length' => 'O campo ip_address não pode exceder 45 caracteres',
            ],
            'user_agent' => [
                'max_length' => 'O campo user_agent não pode exceder 500 caracteres',
            ],
        ];
    }
}
