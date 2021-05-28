<?php namespace App\Repositories\Payments;

use App\Payment;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class PaymentRepository
{

    public function store($request_payload): Payment
    {
        $payment = new Payment();
        $payment->uuid = Uuid::generate(4)->string;
        $payment->user_id = $request_payload->user_id;
        $payment->order_number = $request_payload->order_number;
        $payment->order_date = $request_payload->order_date;
        $payment->amount_due = 0;
        $payment->save();

        return $payment;
    }

    public function update($request_payload)
    {
        return Payment::where([
            'user_id' => $request_payload->user_id,
            'order_number' => $request_payload->order_number
        ])->update([
            'payment_method_id' => $request_payload->payment_method_id,
            'amount_due' => $request_payload->amount_due,
            'reference_number' => $request_payload->reference_number,
            'payment_date' => $request_payload->payment_date
        ]);
    }

    public function destroy($request_payload)
    {
        return Payment::where([
            'user_id' => $request_payload->user_id,
            'order_number' => $request_payload->order_number
        ])->delete();
    }

    public function show($request_payload)
    {
        return Payment::where([
            'order_number' => "ORD/0001",
            'user_id' => $request_payload->user_id
        ])
            ->whereNull('deleted_at')
            ->first();
    }
}
