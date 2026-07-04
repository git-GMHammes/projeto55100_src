<?php

namespace App\Models\V1\User\UserActionLogs;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela user_action_logs.
 *
 * Responsável por todas as operações CRUD diretas na tabela física.
 *
 * Tabela: user_action_logs
 * DDL: id (bigint PK auto), user_id (FK nullable), action, entity, entity_id,
 *      old_value (json), new_value (json), ip_address, user_agent, created_at
 *
 * Sem updated_at e sem deleted_at — useTimestamps e useSoftDeletes são false.
 * created_at é gerenciado pelo banco via DEFAULT CURRENT_TIMESTAMP.
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup        = DB_GROUP_001;
    protected $table          = 'user_action_logs';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;

    /**
     * Campos que podem ser inseridos via Model.
     * Exclui: id (PK), created_at (DEFAULT no banco).
     */
    protected $allowedFields = [
        'user_id',
        'action',
        'entity',
        'entity_id',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent',
    ];

    /**
     * Campos de texto que usam LIKE %valor% no find.
     */
    protected array $likeFields = [
        'action',
        'entity',
        'ip_address',
        'user_agent',
    ];

    /** Campos válidos para ordenação */
    protected array $sortableFields = [
        'id',
        'user_id',
        'action',
        'entity',
        'entity_id',
        'created_at',
    ];

    /** Campos utilizados na busca textual (GET /search) */
    public array $searchFields = [
        'action',
        'entity',
        'ip_address',
        'user_agent',
    ];
}
