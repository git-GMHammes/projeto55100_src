<?php

namespace App\Services\V1\User\UserRefreshTokens;

use App\Models\V1\User\UserRefreshTokens\SqlTableModel;
use App\Services\V1\BaseTableService;

/**
 * Service de negócio para o módulo UserRefreshTokens.
 *
 * Toda a lógica genérica (leitura, escrita, exclusão) está em BaseTableService.
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
}
