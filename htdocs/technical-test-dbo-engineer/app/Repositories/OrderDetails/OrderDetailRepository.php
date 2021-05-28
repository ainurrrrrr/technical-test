<?php namespace App\Repositories\OrderDetails;

use App\OrderDetail;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class OrderDetailRepository
{

    public function store($request_payload): array
    {
        $order_details = [];
        foreach ($request_payload->items as $item) {
            $order_detail = new OrderDetail();
            $order_detail->uuid = Uuid::generate(4)->string;
            $order_detail->user_id = $request_payload->user_id;
            $order_detail->order_id = $request_payload->order_id;
            $order_detail->product_id = $item->product_id;
            $order_detail->product_name = $item->product_name;
            $order_detail->quantity = $item->quantity;
            $order_detail->price = $item->price;
            $order_detail->discount = $item->discount;
            $order_detail->description = $item->description;
            $order_detail->weight = $item->weight;
            $order_detail->save();

            array_push($order_details, $order_detail);
        }

        return $order_details;
    }

    public function update($request_payload)
    {
        return OrderDetail::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id,
            'order_id' => $request_payload->order_id
        ])->update([
            'product_id' => $request_payload->product_id,
            'product_name' => $request_payload->product_name,
            'quantity' => $request_payload->quantity,
            'price' => $request_payload->price,
            'discount' => $request_payload->discount,
            'description' => $request_payload->description,
            'weight' => $request_payload->weight,
        ]);
    }

    public function destroy($request_payload)
    {
        return OrderDetail::where([
            'user_id' => $request_payload->user_id,
            'order_id' => $request_payload->order_id
        ])->delete();
    }

    public function show($order_id, $user_id)
    {
        return OrderDetail::where([
                'order_id' => $order_id,
                'user_id' => $user_id
            ])
            ->whereNull('deleted_at')
            ->get();
    }
}
