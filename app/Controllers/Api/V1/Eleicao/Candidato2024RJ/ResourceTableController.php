<?php

namespace App\Controllers\Api\V1\Eleicao\Candidato2024RJ;

use App\Controllers\Api\V1\BaseResourceTableController;
use App\Requests\V1\Eleicao\Candidato2024RJ\CreateRequest;
use App\Requests\V1\Eleicao\Candidato2024RJ\UpdateRequest;
use App\Services\V1\Eleicao\Candidato2024RJ\Processor;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ResourceTableController extends BaseResourceTableController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->processor = new Processor();
    }

    protected function getCreateRules(): array
    {
        return (new CreateRequest())->rules();
    }

    protected function getUpdateRules(): array
    {
        return (new UpdateRequest())->rules();
    }
}
