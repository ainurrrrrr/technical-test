<?php

namespace App\Services\Products;

use App\Services\Service;
use App\BusinessLogic\Products\GetAllProductBusinessLogic;
use App\BusinessLogic\Products\StoreProductBusinessLogic;
use App\BusinessLogic\Products\ShowProductBusinessLogic;
use App\BusinessLogic\Products\UpdateProductBusinessLogic;
use App\BusinessLogic\Products\DestroyProductBusinessLogic;

class ProductService extends Service
{
    public function getAll($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            GetAllProductBusinessLogic::class,
        ]);

        return $responses->get(GetAllProductBusinessLogic::class);
    }

    public function store($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            StoreProductBusinessLogic::class,
        ]);

        return $responses->get(StoreProductBusinessLogic::class);
    }

    public function show($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            ShowProductBusinessLogic::class,
        ]);

        return $responses->get(ShowProductBusinessLogic::class);
    }

    public function update($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            UpdateProductBusinessLogic::class,
        ]);

        return $responses->get(UpdateProductBusinessLogic::class);
    }

    public function destroy($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            DestroyProductBusinessLogic::class,
        ]);

        return $responses->get(DestroyProductBusinessLogic::class);
    }
}
