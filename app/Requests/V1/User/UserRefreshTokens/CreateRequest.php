<?php

namespace App\Requests\V1\User\UserRefreshTokens;

/**
 * Regras de validação para POST /create (tabela user_refresh_tokens).
 *
 * DDL de referência:
 *   user_id    INT UNSIGNED NULL  — FK user_users.id
 *   token_hash VARCHAR(255) NOT NULL — SHA-256 do refresh token
 *   expires_at DATETIME     NOT NULL
 *   used_at    DATETIME     NULL
 *   ip_address VARCHAR(45)  NULL
 *   user_agent VARCHAR(500) NULL
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'user_id'    => 'permit_empty|is_natural_no_zero',
            'token_hash' => 'required|string|max_length[255]',
            'expires_at' => 'required|valid_date',
            'used_at'    => 'permit_empty|valid_date',
            'ip_address' => 'permit_empty|string|max_length[45]',
            'user_agent' => 'permit_empty|string|max_length[500]',
        ];
    }

    public function messages(): array
    {
        return [
            'token_hash' => [
                'required'   => 'O campo token_hash é obrigatório',
                'max_length' => 'O campo token_hash não pode exceder 255 caracteres',
            ],
            'expires_at' => [
                'required'   => 'O campo expires_at é obrigatório',
                'valid_date' => 'O campo expires_at deve ser uma data válida',
            ],
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
