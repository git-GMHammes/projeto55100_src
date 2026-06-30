<?php

namespace App\Requests\V1\User\UserActionLogs;

/**
 * Regras de validação para PUT /update/{id} (tabela user_action_logs).
 *
 * Logs de auditoria são imutáveis — nenhum campo é alterável após o insert.
 */
class UpdateRequest
{
    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
