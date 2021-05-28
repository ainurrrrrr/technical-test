<?php

namespace App\BusinessLogic\Orders;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Orders\OrderRepository;

class StoreOrderBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new OrderRepository();
    }

    public function run()
    {
        $order = $this->repository->store($this->getScopes());
        $this->putScope('order_id', $order['uuid']);

        return $this->getScope('order_id');
    }
}
