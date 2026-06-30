<?php

namespace App\Models\V1\User\UserUserData;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela user_user_data.
 *
 * Responsável por todas as operações CRUD diretas na tabela física.
 *
 * Tabela: user_user_data
 * DDL: id (int PK auto), user_id (FK unique nullable), full_name,
 *      cpf (varbinary AES_ENCRYPT), birth_date, gender (enum M/F/NB/ND),
 *      phone, address_street, address_number, address_complement,
 *      address_neighborhood, address_city, address_state, address_zipcode,
 *      lgpd_consent_at, lgpd_consent_ip, data_retention_until,
 *      created_at, updated_at, deleted_at
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup        = DB_GROUP_001;
    protected $table          = 'user_user_data';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;

    /**
     * Campos excluídos de qualquer retorno de consulta via Model.
     * cpf é armazenado como AES_ENCRYPT — nunca expor o binário cru.
     */
    protected $hidden = [
        'cpf',
    ];

    /**
     * Campos que podem ser inseridos/atualizados via Model.
     * Exclui: id (PK), created_at/updated_at/deleted_at (timestamps).
     */
    protected $allowedFields = [
        'user_id',
        'full_name',
        'cpf',
        'birth_date',
        'gender',
        'phone',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zipcode',
        'lgpd_consent_at',
        'lgpd_consent_ip',
        'data_retention_until',
    ];

    /**
     * Campos de texto que usam LIKE %valor% no find.
     * cpf excluído — é binário criptografado, sem busca textual.
     */
    protected array $likeFields = [
        'full_name',
        'phone',
        'address_street',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zipcode',
    ];

    /** Campos válidos para ordenação */
    protected array $sortableFields = [
        'id',
        'user_id',
        'full_name',
        'birth_date',
        'gender',
        'address_city',
        'address_state',
        'created_at',
        'updated_at',
    ];

    /** Campos utilizados na busca textual (GET /search) */
    public array $searchFields = [
        'full_name',
        'phone',
        'address_city',
        'address_state',
        'address_zipcode',
    ];
}
