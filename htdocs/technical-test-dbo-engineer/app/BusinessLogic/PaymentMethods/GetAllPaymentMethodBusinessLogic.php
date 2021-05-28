<?php

namespace App\BusinessLogic\PaymentMethods;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\PaymentMethods\PaymentMethodRepository;

class GetAllPaymentMethodBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new PaymentMethodRepository();
    }

    public function run()
    {
        return $this->repository->getAllPaymentMethod($this->getScopes());
    }
}
