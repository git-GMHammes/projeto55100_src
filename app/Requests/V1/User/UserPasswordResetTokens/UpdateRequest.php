<?php

namespace App\Requests\V1\User\UserPasswordResetTokens;

/**
 * Regras de validação para PUT /update/{id} (tabela user_password_reset_tokens).
 *
 * Apenas used_at é mutável após a criação.
 * user_id, token_hash, email e expires_at são imutáveis (auditoria).
 */
class UpdateRequest
{
    public function rules(): array
    {
        return [
            'used_at' => 'permit_empty|string',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
