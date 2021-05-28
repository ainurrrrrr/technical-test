<?php

namespace App\BusinessLogic\OrderDetails;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\OrderDetails\OrderDetailRepository;

class DestroyOrderDetailBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new OrderDetailRepository();
    }

    public function run()
    {
        return $this->repository->destroy($this->getScopes());
    }
}
