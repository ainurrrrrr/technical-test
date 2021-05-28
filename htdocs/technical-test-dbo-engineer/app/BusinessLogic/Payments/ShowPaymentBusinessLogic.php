<?php

namespace App\BusinessLogic\Payments;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Payments\PaymentRepository;

class ShowPaymentBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new PaymentRepository();
    }

    public function run()
    {
        return $this->repository->show($this->getScopes());
    }
}
