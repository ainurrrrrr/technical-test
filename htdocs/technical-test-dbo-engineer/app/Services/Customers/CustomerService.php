<?php

namespace App\Services\Customers;

use App\Services\Service;
use App\BusinessLogic\Customers\GetAllCustomerBusinessLogic;
use App\BusinessLogic\Customers\StoreCustomerBusinessLogic;
use App\BusinessLogic\Customers\ShowCustomerBusinessLogic;
use App\BusinessLogic\Customers\UpdateCustomerBusinessLogic;
use App\BusinessLogic\Customers\DestroyCustomerBusinessLogic;

class CustomerService extends Service
{
    public function getAll($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            GetAllCustomerBusinessLogic::class,
        ]);

        return $responses->get(GetAllCustomerBusinessLogic::class);
    }

    public function store($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            StoreCustomerBusinessLogic::class,
        ]);

        return $responses->get(StoreCustomerBusinessLogic::class);
    }

    public function show($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            ShowCustomerBusinessLogic::class,
        ]);

        return $responses->get(ShowCustomerBusinessLogic::class);
    }

    public function update($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            UpdateCustomerBusinessLogic::class,
        ]);

        return $responses->get(UpdateCustomerBusinessLogic::class);
    }

    public function destroy($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            DestroyCustomerBusinessLogic::class,
        ]);

        return $responses->get(DestroyCustomerBusinessLogic::class);
    }
}
