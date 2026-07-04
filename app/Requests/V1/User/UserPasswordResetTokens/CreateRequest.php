<?php

namespace App\Requests\V1\User\UserPasswordResetTokens;

/**
 * Regras de validação para POST /create (tabela user_password_reset_tokens).
 *
 * DDL de referência:
 *   user_id    INT UNSIGNED  NULL
 *   token_hash VARCHAR(255)  NOT NULL UNIQUE — SHA-256 do token enviado por e-mail
 *   email      VARCHAR(191)  NOT NULL
 *   expires_at DATETIME      NOT NULL
 *   used_at    DATETIME      NULL
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'user_id'    => 'permit_empty|is_natural_no_zero',
            'token_hash' => 'required|string|min_length[64]|max_length[255]',
            'email'      => 'required|string|max_length[191]',
            'expires_at' => 'required|string',
            'used_at'    => 'permit_empty|string',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id' => [
                'is_natural_no_zero' => 'O campo user_id deve ser um inteiro positivo',
            ],
            'token_hash' => [
                'required'   => 'O campo token_hash é obrigatório',
                'min_length' => 'O token_hash deve ter no mínimo 64 caracteres (SHA-256)',
                'max_length' => 'O token_hash não pode exceder 255 caracteres',
            ],
            'email' => [
                'required'   => 'O campo email é obrigatório',
                'max_length' => 'O campo email não pode exceder 191 caracteres',
            ],
            'expires_at' => [
                'required' => 'O campo expires_at é obrigatório',
            ],
        ];
    }
}
