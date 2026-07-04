<?php

namespace App\Services\V1\User\UserUsers;

use App\Models\V1\User\UserUsers\SqlTableModel;
use App\Services\V1\BaseTableService;

/**
 * Service de negócio para o módulo UserUsers.
 *
 * Toda a lógica genérica (leitura, escrita, exclusão) está em BaseTableService.
 * Este Processor valida unicidade de email e username, e aplica bcrypt na senha.
 *
 * Métodos: find, getGrouped, search, get, getAll, getNoPagination,
 *          getDeleted, getDeletedAll, create, update,
 *          deleteSoft, deleteRestore, deleteHard, clearDeleted
 */
class Processor extends BaseTableService
{
    protected SqlTableModel $tableModel;

    public function __construct()
    {
        $this->tableModel = new SqlTableModel();
    }

    // -------------------------------------------------------------------------
    // Hooks de validação
    // -------------------------------------------------------------------------

    protected function validateOnCreate(array $data): ?array
    {
        if (!empty($data['email']) && $this->tableModel->existsByEmail($data['email'])) {
            return ['success' => false, 'message' => 'E-mail já cadastrado', 'code' => 409];
        }

        if (!empty($data['username']) && $this->tableModel->existsByUsername($data['username'])) {
            return ['success' => false, 'message' => 'Username já cadastrado', 'code' => 409];
        }

        return null;
    }

    protected function validateOnUpdate(int $id, array $data): ?array
    {
        if (!empty($data['email']) && $this->tableModel->existsByEmail($data['email'], $id)) {
            return ['success' => false, 'message' => 'E-mail já cadastrado', 'code' => 409];
        }

        if (!empty($data['username']) && $this->tableModel->existsByUsername($data['username'], $id)) {
            return ['success' => false, 'message' => 'Username já cadastrado', 'code' => 409];
        }

        return null;
    }

    // -------------------------------------------------------------------------
    // Hook de preparação de dados
    // -------------------------------------------------------------------------

    protected function prepareData(array $data): array
    {
        if (!empty($data['password_hash'])) {
            $data['password_hash'] = password_hash($data['password_hash'], PASSWORD_BCRYPT);
        }

        return $data;
    }

    protected function prepareUpdateData(int $id, array $data): array
    {
        unset($data['password_hash']);

        return $data;
    }
}
