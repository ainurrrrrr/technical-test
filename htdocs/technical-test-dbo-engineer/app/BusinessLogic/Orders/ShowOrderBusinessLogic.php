<?php

namespace App\BusinessLogic\Orders;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Orders\OrderRepository;

class ShowOrderBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new OrderRepository();
    }

    public function run()
    {
        return $this->repository->show($this->getScope('uuid'), $this->getScope('user_id'));
    }
}
