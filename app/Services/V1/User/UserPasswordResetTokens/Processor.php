<?php

namespace App\Services\V1\User\UserPasswordResetTokens;

use App\Models\V1\User\UserPasswordResetTokens\SqlTableModel;
use App\Services\V1\BaseTableService;

/**
 * Service de negócio para o módulo UserPasswordResetTokens.
 *
 * Toda a lógica genérica (leitura, escrita, exclusão) está em BaseTableService.
 * O fluxo de reset de senha (geração de token, envio de e-mail, aplicação de nova
 * senha) é responsabilidade de um Processor de autenticação dedicado, que
 * orquestra múltiplos modelos e usa os métodos específicos do SqlTableModel
 * (findActiveByTokenHash, markAsUsed, invalidateActiveByUserId).
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
