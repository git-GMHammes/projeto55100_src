<?php

namespace App\Models\V1\Eleicao\Candidato2024RJ;

use App\Models\V1\BaseTableModel;

/**
 * Model de escrita para a tabela candidato_2024_RJ.
 *
 * Tabela: candidato_2024_RJ
 * DDL: id (varchar PK), CD_MUNICIPIO, CD_CARGO, DS_CARGO, NM_MUNICIPIO,
 *      NR_CANDIDATO, NM_CANDIDATO, NM_SOCIAL_CANDIDATO, SG_PARTIDO,
 *      NM_PARTIDO, QT_VOTOS_NOMINAIS, DS_SIT_TOT_TURNO
 */
class SqlTableModel extends BaseTableModel
{
    protected $DBGroup          = DB_GROUP_001;
    protected $table            = 'candidato_2024_RJ';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    /**
     * id incluído pois a PK não é auto-increment e deve ser fornecida no insert.
     */
    protected $allowedFields = [
        'id',
        'CD_MUNICIPIO',
        'CD_CARGO',
        'DS_CARGO',
        'NM_MUNICIPIO',
        'NR_CANDIDATO',
        'NM_CANDIDATO',
        'NM_SOCIAL_CANDIDATO',
        'SG_PARTIDO',
        'NM_PARTIDO',
        'QT_VOTOS_NOMINAIS',
        'DS_SIT_TOT_TURNO',
    ];

    protected array $likeFields = [
        'NM_MUNICIPIO',
        'NR_CANDIDATO',
        'NM_CANDIDATO',
        'NM_SOCIAL_CANDIDATO',
        'SG_PARTIDO',
        'NM_PARTIDO',
    ];

    protected array $sortableFields = [
        'id',
        'CD_MUNICIPIO',
        'CD_CARGO',
        'DS_CARGO',
        'NM_MUNICIPIO',
        'NR_CANDIDATO',
        'NM_CANDIDATO',
        'NM_SOCIAL_CANDIDATO',
        'SG_PARTIDO',
        'NM_PARTIDO',
        'QT_VOTOS_NOMINAIS',
        'DS_SIT_TOT_TURNO',
    ];

    public array $searchFields = [
        'NM_MUNICIPIO',
        'NR_CANDIDATO',
        'NM_CANDIDATO',
        'NM_SOCIAL_CANDIDATO',
        'SG_PARTIDO',
        'NM_PARTIDO',
    ];
}
