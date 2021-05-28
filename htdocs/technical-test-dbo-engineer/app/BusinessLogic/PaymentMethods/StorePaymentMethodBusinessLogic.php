<?php

namespace App\BusinessLogic\PaymentMethods;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\PaymentMethods\PaymentMethodRepository;

class StorePaymentMethodBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new PaymentMethodRepository();
    }

    public function run()
    {
        return $this->repository->store($this->getScopes());
    }
}
