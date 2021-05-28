<?php

namespace App\Services\PaymentMethods;

use App\Services\Service;
use App\BusinessLogic\PaymentMethods\GetAllPaymentMethodBusinessLogic;
use App\BusinessLogic\PaymentMethods\StorePaymentMethodBusinessLogic;
use App\BusinessLogic\PaymentMethods\ShowPaymentMethodBusinessLogic;
use App\BusinessLogic\PaymentMethods\UpdatePaymentMethodBusinessLogic;
use App\BusinessLogic\PaymentMethods\DestroyPaymentMethodBusinessLogic;

class PaymentMethodService extends Service
{
    public function getAll($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            GetAllPaymentMethodBusinessLogic::class,
        ]);

        return $responses->get(GetAllPaymentMethodBusinessLogic::class);
    }

    public function store($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            StorePaymentMethodBusinessLogic::class,
        ]);

        return $responses->get(StorePaymentMethodBusinessLogic::class);
    }

    public function show($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            ShowPaymentMethodBusinessLogic::class,
        ]);

        return $responses->get(ShowPaymentMethodBusinessLogic::class);
    }

    public function update($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            UpdatePaymentMethodBusinessLogic::class,
        ]);

        return $responses->get(UpdatePaymentMethodBusinessLogic::class);
    }

    public function destroy($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            DestroyPaymentMethodBusinessLogic::class,
        ]);

        return $responses->get(DestroyPaymentMethodBusinessLogic::class);
    }
}
