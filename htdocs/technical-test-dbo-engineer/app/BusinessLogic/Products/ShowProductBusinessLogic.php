<?php

namespace App\BusinessLogic\Products;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Products\ProductRepository;

class ShowProductBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new ProductRepository();
    }

    public function run()
    {
        return $this->repository->show($this->getScope('uuid'), $this->getScope('user_id'));
    }
}
