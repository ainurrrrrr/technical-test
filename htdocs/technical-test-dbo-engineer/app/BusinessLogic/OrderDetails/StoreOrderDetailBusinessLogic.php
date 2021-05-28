<?php

namespace App\BusinessLogic\OrderDetails;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\OrderDetails\OrderDetailRepository;

class StoreOrderDetailBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new OrderDetailRepository();
    }

    public function run()
    {
        $scopes = $this->getScopes();
        $scopes->order_id = $this->getScope('order_id');

        return $this->repository->store($scopes);
    }
}
