<?php

namespace App\Services\V1\Eleicao\Candidato2022RJ;

use App\Models\V1\Eleicao\Candidato2022RJ\SqlTableModel;
use App\Models\V1\Eleicao\Candidato2022RJ\SqlViewModel;
use App\Services\V1\BaseTableService;

/**
 * Service de leitura/escrita para o módulo Candidato2022RJ.
 *
 * Toda a lógica genérica de tabela e view está em BaseTableService/BaseViewService.
 * Sem hooks de validação adicionais — id é fornecido pelo cliente (PK não auto-increment).
 */
class Processor extends BaseTableService
{
    protected SqlTableModel $tableModel;
    protected SqlViewModel $viewModel;

    public function __construct()
    {
        $this->tableModel = new SqlTableModel();
        $this->viewModel  = new SqlViewModel();
    }
}
