<?php namespace App\Repositories\Orders;

use App\Order;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class OrderRepository
{

    public function GetAllOrder($request_payload): array
    {
        $user_id = $request_payload->user_id;
        $offset = $request_payload->first;
        $rows = $request_payload->rows;
        $filters = $request_payload->filters;
        $sort_by = ($request_payload->sortOrder == 1) ? 'asc' : 'desc';
        $sort_field = $this->setSortField($request_payload->sortField);

        $orders = DB::table('orders')
            ->select(
                'orders.uuid',
                'orders.user_id',
                'orders.order_number',
                'orders.order_date',
                'orders.status',
                'orders.grand_total',
                'orders.discount',
                'orders.shipping_address',
                'orders.billing_address',
                'orders.phone_number'
            )
            ->where('orders.user_id', $user_id)
            ->whereNull('orders.deleted_at')
            ->join('order_details', 'orders.uuid', '=', 'order_details.order_id')
            ->join('payments', 'orders.order_number', '=', 'payments.order_number')
            ->where(function ($query) use ($filters) {
                if (isset($filters->global)) {
                    $search = $filters->global;
                    $query->OrWhere('orders.order_number', 'like', "%{$search}%")
                        ->OrWhere('orders.status', 'like', "%{$search}%")
                        ->OrWhere('orders.order_date', 'like', "%{$search}%")
                        ->OrWhere('orders.phone_number', 'like', "%{$search}%")
                        ->OrWhere('orders.shipping_address', 'like', "%{$search}%")
                        ->OrWhere('orders.billing_address', 'like', "%{$search}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->order_number)) {
                    $order_number = $filters->order_number;
                    $query->OrWhere('orders.order_number', 'like', "%{$order_number}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->status)) {
                    $status = $filters->status;
                    $query->OrWhere('orders.status', 'like', "%{$status}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->order_date)) {
                    $order_date = $filters->order_date;
                    $query->OrWhere('orders.order_date', 'like', "%{$order_date}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->phone_number)) {
                    $phone_number = $filters->phone_number;
                    $query->OrWhere('orders.phone_number', 'like', "%{$phone_number}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->shipping_address)) {
                    $shipping_address = $filters->shipping_address;
                    $query->OrWhere('orders.shipping_address', 'like', "%{$shipping_address}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->billing_address)) {
                    $billing_address = $filters->billing_address;
                    $query->OrWhere('orders.billing_address', 'like', "%{$billing_address}%");
                }
            })
            ->orderBy($sort_field, $sort_by);

        $collect = $orders->get()->toArray();
        $total_records = collect($collect)->count();

        $results = $orders->skip($offset)->take($rows)->get()->toArray();

        return [
            'orders' => $results,
            'total_records' => $total_records,
            'message' => 'Success get orders'
        ];
    }

    public function setSortField($sort_field): string
    {
        if ($sort_field != null && $sort_field != 'undefined' && $sort_field != '') {
            switch ($sort_field) {
                case 'order_number':
                    return 'orders.order_number';
                case 'status':
                    return 'orders.status';
                case 'order_date':
                    return 'orders.order_date';
                case 'phone_number':
                    return 'orders.phone_number';
                case 'shipping_address':
                    return 'orders.shipping_address';
                case 'billing_address':
                    return 'orders.billing_address';
                default:
                    return 'orders.created_at';
            }
        }

        return 'created_at';

    }

    public function store($request_payload): Order
    {
        $order = new Order();
        $order->uuid = Uuid::generate(4)->string;
        $order->user_id = $request_payload->user_id;
        $order->order_number = $request_payload->order_number;
        $order->order_date = $request_payload->order_date;
        $order->status = $request_payload->status;
        $order->grand_total = $request_payload->grand_total;
        $order->discount = $request_payload->discount;
        $order->shipping_address = $request_payload->shipping_address;
        $order->billing_address = $request_payload->billing_address;
        $order->phone_number = $request_payload->phone_number;
        $order->shipping_price = $request_payload->shipping_price;
        $order->save();

        return $order;
    }

    public function show($uuid, $user_id)
    {
        return DB::table('orders')
            ->where([
                'uuid' => $uuid,
                'user_id' => $user_id
            ])
            ->whereNull('deleted_at')
            ->first();
    }

    public function update($request_payload)
    {
        return Order::where([
            'order_number' => $request_payload->order_number,
            'user_id' => $request_payload->user_id
        ])->update([
            'status' => $request_payload->status,
        ]);
    }

    public function destroy($request_payload)
    {
        return Order::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->delete();
    }
}
