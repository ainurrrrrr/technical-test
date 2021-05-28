<?php

namespace App\Services\Payments;

use App\Services\Service;
use App\BusinessLogic\Payments\ShowPaymentBusinessLogic;
use App\BusinessLogic\Orders\UpdateOrderBusinessLogic;
use App\BusinessLogic\Payments\UpdatePaymentBusinessLogic;

class PaymentService extends Service
{

    public function show($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            ShowPaymentBusinessLogic::class,
        ]);

        return $responses->get(ShowPaymentBusinessLogic::class);
    }

    public function update($request_payload)
    {
        $scope = $request_payload;
        $scope['status'] = "paid";

        $responses = $this->business($scope, [
            UpdatePaymentBusinessLogic::class,
            UpdateOrderBusinessLogic::class,
        ]);

        return $responses->get(UpdatePaymentBusinessLogic::class);
    }
}
