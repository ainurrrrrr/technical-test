<?php

namespace App\BusinessLogic\Uoms;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Uoms\UomRepository;

class DestroyUomBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new UomRepository();
    }

    public function run()
    {
        return $this->repository->destroy($this->getScopes());
    }
}
