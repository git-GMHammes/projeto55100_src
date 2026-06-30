<?php

namespace App\Services\V1\User\UserUserData;

use App\Models\V1\User\UserUserData\SqlTableModel;
use App\Services\V1\BaseTableService;

/**
 * Service de negócio para o módulo UserUserData.
 *
 * Toda a lógica genérica (leitura, escrita, exclusão) está em BaseTableService.
 * cpf é armazenado via AES_ENCRYPT — a criptografia é aplicada na camada SQL
 * pelo banco; este Processor não manipula o campo diretamente.
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
    // Hook de preparação de dados
    // -------------------------------------------------------------------------

    protected function prepareUpdateData(int $id, array $data): array
    {
        unset($data['user_id']);

        return $data;
    }
}
