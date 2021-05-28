<?php

namespace App\BusinessLogic\Customers;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Customers\CustomerRepository;

class ShowCustomerBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new CustomerRepository();
    }

    public function run()
    {
        return $this->repository->show($this->getScope('uuid'), $this->getScope('user_id'));
    }
}
