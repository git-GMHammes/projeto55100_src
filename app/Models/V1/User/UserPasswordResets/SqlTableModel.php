<?php

namespace App\Models\V1\User\UserPasswordResets;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela user_password_reset_tokens.
 *
 * Responsável por todas as operações CRUD diretas na tabela física.
 *
 * Tabela: user_password_reset_tokens
 * DDL: id, user_id, token_hash, email, expires_at, used_at, created_at
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'user_password_reset_tokens';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    /**
     * Campos que podem ser inseridos/atualizados via Model.
     */
    protected $allowedFields = [
        'user_id',
        'token_hash',
        'email',
        'expires_at',
        'used_at',
    ];

    protected array $likeFields = [];

    protected array $sortableFields = [
        'id',
        'user_id',
        'expires_at',
        'used_at',
        'created_at',
    ];

    public array $searchFields = [];

    // -------------------------------------------------------------------------
    // Métodos específicos do fluxo de reset de senha
    // -------------------------------------------------------------------------

    /**
     * Busca um token ativo: não utilizado e não expirado.
     */
    public function findActiveByTokenHash(string $hash): ?array
    {
        return $this->where('token_hash', $hash)
                    ->where('used_at IS NULL', null, false)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Invalida (marca como usado) todos os tokens pendentes de um usuário.
     */
    public function softDeleteActiveByUserId(int $userId): void
    {
        $this->db->table($this->table)
                 ->where('user_id', $userId)
                 ->where('used_at IS NULL', null, false)
                 ->where('expires_at >', date('Y-m-d H:i:s'))
                 ->update(['used_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Marca o token como utilizado, impedindo reuso.
     */
    public function markAsUsed(int $id): void
    {
        $this->db->table($this->table)
                 ->where($this->primaryKey, $id)
                 ->update(['used_at' => date('Y-m-d H:i:s')]);
    }
}
