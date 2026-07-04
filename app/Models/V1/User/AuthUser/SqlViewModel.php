<?php

namespace App\Models\V1\User\AuthUser;

use App\Models\V1\BaseViewModel;

/**
 * Model de leitura para a view view_auth_user.
 *
 * Responsável exclusivamente por consultas (read-only).
 * A view une user_users (uu) com user_user_data (uud) e user_profiles (up).
 *
 * Prefixos na view:
 *   um_ = user_users (username, password_hash, status)
 *   uc_ = user_user_data (full_name, cpf, phone, address, etc.)
 *   up_ = user_profiles (name)
 *
 * O campo deleted_at reflete user_users.deleted_at.
 *
 * Todos os métodos de leitura genéricos estão disponíveis via BaseViewModel.
 * Este model expõe apenas os métodos específicos de autenticação.
 */
class SqlViewModel extends BaseViewModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'view_auth_user';
    protected $primaryKey = 'id';

    /**
     * Campos de texto que usam LIKE %valor% no findPaginatedView.
     */
    protected array $likeFields = [
        'um_user',
        'uc_name',
        'uc_cpf',
        'uc_whatsapp',
        'uc_phone',
        'uc_mail',
        'uc_address',
        'uc_profile',
        'uc_zip_code',
    ];

    /** Campos válidos para ordenação */
    protected array $sortableFields = [
        'id',
        'um_user',
        'um_is_active',
        'uc_name',
        'uc_cpf',
        'uc_mail',
        'uc_whatsapp',
        'created_at',
        'updated_at',
    ];

    /** Campos utilizados na busca textual (GET /search) */
    public array $searchFields = [
        'um_user',
        'uc_name',
        'uc_cpf',
        'uc_mail',
        'uc_whatsapp',
        'uc_phone',
        'uc_address',
    ];

    // -------------------------------------------------------------------------
    // Consultas específicas de autenticação
    // -------------------------------------------------------------------------

    /**
     * Busca um usuário ativo na view pelo campo um_user (login).
     *
     * @param  string $user Valor do campo um_user
     * @return array|null   Registro completo da view ou null se não encontrado
     */
    public function findByUser(string $user): ?array
    {
        $result = $this->db->table($this->table)
            ->where('um_user', $user)
            ->where('um_is_active', 1)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    /**
     * Busca um usuário ativo na view pelo ID.
     *
     * @param  int $userId ID do usuário
     * @return array|null  Registro completo da view ou null se não encontrado
     */
    public function findById(int $userId): ?array
    {
        $result = $this->db->table($this->table)
            ->where('id', $userId)
            ->where('um_is_active', 1)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    /**
     * Busca um usuário ativo na view pelo campo uc_mail.
     *
     * @param  string $mail Valor do campo uc_mail
     * @return array|null   Registro completo da view ou null se não encontrado
     */
    public function findByMail(string $mail): ?array
    {
        $result = $this->db->table($this->table)
            ->where('uc_mail', $mail)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }
}
