<?php

namespace App\Services\V1\Eleicao\Candidato2024RJ;

use App\Models\V1\Eleicao\Candidato2024RJ\SqlTableModel;
use App\Models\V1\Eleicao\Candidato2024RJ\SqlViewModel;
use App\Services\V1\BaseTableService;

/**
 * Service de leitura/escrita para o módulo Candidato2024RJ.
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
