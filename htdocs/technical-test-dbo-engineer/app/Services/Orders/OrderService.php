<?php

namespace App\Services\Orders;

use App\Services\Service;
use App\BusinessLogic\Orders\GetAllOrderBusinessLogic;
use App\BusinessLogic\Orders\StoreOrderBusinessLogic;
use App\BusinessLogic\Orders\ShowOrderBusinessLogic;
use App\BusinessLogic\Orders\UpdateOrderBusinessLogic;
use App\BusinessLogic\Orders\DestroyOrderBusinessLogic;
use App\BusinessLogic\OrderDetails\StoreOrderDetailBusinessLogic;
use App\BusinessLogic\OrderDetails\ShowOrderDetailBusinessLogic;
use App\BusinessLogic\OrderDetails\DestroyOrderDetailBusinessLogic;
use App\BusinessLogic\Payments\StorePaymentBusinessLogic;
use App\BusinessLogic\Payments\ShowPaymentBusinessLogic;
use App\BusinessLogic\Payments\DestroyPaymentBusinessLogic;

class OrderService extends Service
{
    public function getAll($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            GetAllOrderBusinessLogic::class,
        ]);

        $responses = $responses->get(GetAllOrderBusinessLogic::class);

        foreach ($responses['orders'] as $order) {

            $order_scope = [
                'order_id' => $order->uuid,
                'user_id' => $order->user_id
            ];

            $order_details = $this->business($order_scope, [
                ShowOrderDetailBusinessLogic::class,
            ]);
            $order->order_details = $order_details->get(ShowOrderDetailBusinessLogic::class);

            $payment_scope = [
                'order_number' => $order->order_number,
                'user_id' => $order->user_id
            ];

            $payments = $this->business($payment_scope, [
                ShowPaymentBusinessLogic::class,
            ]);
            $order->payments = $payments->get(ShowPaymentBusinessLogic::class);
        }

        return $responses;
    }

    public function store($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            StoreOrderBusinessLogic::class,
            StoreOrderDetailBusinessLogic::class,
            StorePaymentBusinessLogic::class,
        ]);

        return $responses->get(StoreOrderBusinessLogic::class);
    }

    public function show($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            ShowOrderBusinessLogic::class,
        ]);

        $responses = $responses->get(ShowOrderBusinessLogic::class);

        if (!empty($responses)) {
            $order_scope = [
                'order_id' => $responses->uuid,
                'user_id' => $responses->user_id
            ];

            $order_details = $this->business($order_scope, [
                ShowOrderDetailBusinessLogic::class,
            ]);

            $responses->order_details = $order_details->get(ShowOrderDetailBusinessLogic::class);

            $payment_scope = [
                'order_number' => $responses->order_number,
                'user_id' => $responses->user_id
            ];

            $payments = $this->business($payment_scope, [
                ShowPaymentBusinessLogic::class,
            ]);

            $responses->payments = $payments->get(ShowPaymentBusinessLogic::class);
        }

        return $responses;
    }

    public function update($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            UpdateOrderBusinessLogic::class,
        ]);

        return $responses->get(UpdateOrderBusinessLogic::class);
    }

    public function destroy($request_payload)
    {
        $scope = $request_payload;
        $scope['order_id'] = $scope['uuid'];

        $responses = $this->business($scope, [
            DestroyOrderBusinessLogic::class,
            DestroyOrderDetailBusinessLogic::class,
            DestroyPaymentBusinessLogic::class,
        ]);

        return $responses->get(DestroyOrderBusinessLogic::class);
    }
}
