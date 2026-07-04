<?php

namespace App\Models\V1\User\UserProfiles;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela user_profiles.
 *
 * Responsável por todas as operações CRUD diretas na tabela física.
 *
 * Tabela: user_profiles
 * DDL: id (int PK auto), name, slug (unique), description,
 *      permissions (json), status (tinyint 1=ativo), created_at, updated_at, deleted_at
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup        = DB_GROUP_001;
    protected $table          = 'user_profiles';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;

    /**
     * Campos que podem ser inseridos/atualizados via Model.
     * Exclui: id (PK), created_at/updated_at/deleted_at (timestamps).
     */
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'permissions',
        'status',
    ];

    /**
     * Campos de texto que usam LIKE %valor% no find.
     */
    protected array $likeFields = [
        'name',
        'slug',
        'description',
    ];

    /** Campos válidos para ordenação */
    protected array $sortableFields = [
        'id',
        'name',
        'slug',
        'status',
        'created_at',
        'updated_at',
    ];

    /** Campos utilizados na busca textual (GET /search) */
    public array $searchFields = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Alias semântico para existsByField aplicado ao campo 'slug'.
     *
     * @param string   $slug      Slug a verificar
     * @param int|null $excludeId ID a ignorar (usado no update)
     */
    public function existsBySlug(string $slug, ?int $excludeId = null): bool
    {
        return $this->existsByField('slug', $slug, $excludeId);
    }
}
