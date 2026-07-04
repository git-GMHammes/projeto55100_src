<?php

namespace App\Services\V1\User\UserActionLogs;

use App\Models\V1\User\UserActionLogs\SqlTableModel;
use App\Services\V1\BaseTableService;

/**
 * Service de negócio para o módulo UserActionLogs.
 *
 * Toda a lógica genérica (leitura, escrita, exclusão) está em BaseTableService.
 * Logs de auditoria são write-once — nenhum hook de validação/preparação é necessário.
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
