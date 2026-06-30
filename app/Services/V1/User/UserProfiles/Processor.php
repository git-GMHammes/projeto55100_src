<?php

namespace App\Services\V1\User\UserProfiles;

use App\Models\V1\User\UserProfiles\SqlTableModel;
use App\Services\V1\BaseTableService;

/**
 * Service de negócio para o módulo UserProfiles.
 *
 * Toda a lógica genérica (leitura, escrita, exclusão) está em BaseTableService.
 * Este Processor valida unicidade do slug antes de insert e update.
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
        if (!empty($data['slug']) && $this->tableModel->existsBySlug($data['slug'])) {
            return ['success' => false, 'message' => 'Slug já cadastrado', 'code' => 409];
        }

        return null;
    }

    protected function validateOnUpdate(int $id, array $data): ?array
    {
        if (!empty($data['slug']) && $this->tableModel->existsBySlug($data['slug'], $id)) {
            return ['success' => false, 'message' => 'Slug já cadastrado', 'code' => 409];
        }

        return null;
    }
}
