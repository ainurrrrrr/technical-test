<?php

namespace App\BusinessLogic\Products;

use stdClass;
use App\BusinessLogic\BusinessLogic;
use App\Repositories\Products\ProductRepository;

class DestroyProductBusinessLogic extends BusinessLogic
{
    private $repository;

    public function __construct(stdClass $scope)
    {
        $this->scopes = $scope;
        $this->repository = new ProductRepository();
    }

    public function run()
    {
        return $this->repository->destroy($this->getScopes());
    }
}
