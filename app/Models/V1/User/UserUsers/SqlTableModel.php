<?php

namespace App\Models\V1\User\UserUsers;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela user_users.
 *
 * Responsável por todas as operações CRUD diretas na tabela física.
 *
 * Tabela: user_users
 * DDL: id (int PK auto), profile_id (FK nullable), username (unique),
 *      email (unique), password_hash, status (enum active/inactive/blocked),
 *      last_login_at, created_at, updated_at, deleted_at
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup        = DB_GROUP_001;
    protected $table          = 'user_users';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;

    /**
     * Campos excluídos de qualquer retorno de consulta via Model.
     * Evita exposição acidental do hash de senha nas respostas da API.
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Campos que podem ser inseridos/atualizados via Model.
     * Exclui: id (PK), created_at/updated_at/deleted_at (timestamps).
     */
    protected $allowedFields = [
        'profile_id',
        'username',
        'email',
        'password_hash',
        'status',
        'last_login_at',
    ];

    /**
     * Campos de texto que usam LIKE %valor% no find.
     */
    protected array $likeFields = [
        'username',
        'email',
    ];

    /** Campos válidos para ordenação */
    protected array $sortableFields = [
        'id',
        'profile_id',
        'username',
        'email',
        'status',
        'last_login_at',
        'created_at',
        'updated_at',
    ];

    /** Campos utilizados na busca textual (GET /search) */
    public array $searchFields = [
        'username',
        'email',
    ];

    /**
     * Alias semântico para existsByField aplicado ao campo 'email'.
     *
     * @param string   $email     E-mail a verificar
     * @param int|null $excludeId ID a ignorar (usado no update)
     */
    public function existsByEmail(string $email, ?int $excludeId = null): bool
    {
        return $this->existsByField('email', $email, $excludeId);
    }

    /**
     * Alias semântico para existsByField aplicado ao campo 'username'.
     *
     * @param string   $username  Username a verificar
     * @param int|null $excludeId ID a ignorar (usado no update)
     */
    public function existsByUsername(string $username, ?int $excludeId = null): bool
    {
        return $this->existsByField('username', $username, $excludeId);
    }
}
