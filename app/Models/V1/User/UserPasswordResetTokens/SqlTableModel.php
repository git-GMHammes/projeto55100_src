<?php

namespace App\Models\V1\User\UserPasswordResetTokens;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela user_password_reset_tokens.
 *
 * Responsável por todas as operações CRUD diretas na tabela física.
 *
 * Tabela: user_password_reset_tokens
 * DDL: id (int PK auto), user_id (FK nullable), token_hash (unique SHA-256),
 *      email, expires_at, used_at, created_at
 *
 * Sem updated_at e sem deleted_at — useTimestamps e useSoftDeletes são false.
 * created_at é gerenciado pelo banco via DEFAULT CURRENT_TIMESTAMP.
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup        = DB_GROUP_001;
    protected $table          = 'user_password_reset_tokens';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;

    /**
     * Campos que podem ser inseridos via Model.
     * Exclui: id (PK), created_at (DEFAULT no banco).
     */
    protected $allowedFields = [
        'user_id',
        'token_hash',
        'email',
        'expires_at',
        'used_at',
    ];

    /**
     * Campos de texto que usam LIKE %valor% no find.
     * token_hash é SHA-256 — busca exata, fora de likeFields.
     */
    protected array $likeFields = [
        'email',
    ];

    /** Campos válidos para ordenação */
    protected array $sortableFields = [
        'id',
        'user_id',
        'expires_at',
        'used_at',
        'created_at',
    ];

    /** Campos utilizados na busca textual (GET /search) */
    public array $searchFields = [
        'email',
    ];

    // -------------------------------------------------------------------------
    // Métodos específicos do fluxo de reset de senha
    // -------------------------------------------------------------------------

    /**
     * Busca um token ativo: não utilizado, não expirado.
     *
     * @param string $hash SHA-256 do token plain recebido pelo usuário
     */
    public function findActiveByTokenHash(string $hash): ?array
    {
        $row = $this->db->table($this->table)
            ->where('token_hash', $hash)
            ->where('used_at IS NULL', null, false)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Marca o token como utilizado, impedindo reuso.
     *
     * @param int $id PK do registro
     */
    public function markAsUsed(int $id): void
    {
        $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update(['used_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Invalida (hard delete) todos os tokens pendentes de um usuário.
     * Chamado antes de emitir novo token para evitar tokens órfãos ativos.
     *
     * @param int $userId ID do usuário em user_users
     */
    public function invalidateActiveByUserId(int $userId): void
    {
        $this->db->table($this->table)
            ->where('user_id', $userId)
            ->where('used_at IS NULL', null, false)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->delete();
    }
}
