<?php

namespace App\BusinessLogic\Orders;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Orders\OrderRepository;

class GetAllOrderBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new OrderRepository();
    }

    public function run()
    {
        return $this->repository->getAllOrder($this->getScopes());
    }
}
