<?php

namespace App\Requests\V1\User\UserActionLogs;

/**
 * Regras de validação para POST /create (tabela user_action_logs).
 *
 * DDL de referência:
 *   user_id    INT UNSIGNED NULL  — FK user_users.id (NULL = ação anônima/sistema)
 *   action     VARCHAR(100) NOT NULL
 *   entity     VARCHAR(100) NULL
 *   entity_id  INT UNSIGNED NULL
 *   old_value  JSON         NULL
 *   new_value  JSON         NULL
 *   ip_address VARCHAR(45)  NULL
 *   user_agent VARCHAR(500) NULL
 */
class CreateRequest
{
    public function rules(): array
    {
        return [
            'user_id'    => 'permit_empty|is_natural_no_zero',
            'action'     => 'required|string|max_length[100]',
            'entity'     => 'permit_empty|string|max_length[100]',
            'entity_id'  => 'permit_empty|is_natural_no_zero',
            'old_value'  => 'permit_empty|string',
            'new_value'  => 'permit_empty|string',
            'ip_address' => 'permit_empty|string|max_length[45]',
            'user_agent' => 'permit_empty|string|max_length[500]',
        ];
    }

    public function messages(): array
    {
        return [
            'action' => [
                'required'   => 'O campo action é obrigatório',
                'max_length' => 'O campo action não pode exceder 100 caracteres',
            ],
            'user_id' => [
                'is_natural_no_zero' => 'O campo user_id deve ser um inteiro positivo',
            ],
            'entity_id' => [
                'is_natural_no_zero' => 'O campo entity_id deve ser um inteiro positivo',
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
