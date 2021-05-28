<?php namespace App\Repositories\PaymentMethods;

use App\PaymentMethod;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class PaymentMethodRepository
{

    public function GetAllPaymentMethod($request_payload): array
    {
        $user_id = $request_payload->user_id;
        $offset = $request_payload->first;
        $rows = $request_payload->rows;
        $filters = $request_payload->filters;
        $sort_by = ($request_payload->sortOrder == 1) ? 'asc' : 'desc';
        $sort_field = $this->setSortField($request_payload->sortField);

        $payment_methods = DB::table('payment_methods')
            ->select(
                'uuid',
                'name',
                'is_active'
            )
            ->where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($filters) {
                if (isset($filters->global)) {
                    $search = $filters->global;
                    $query->OrWhere('name', 'like', "%{$search}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->name)) {
                    $name = $filters->name;
                    $query->OrWhere('name', 'like', "%{$name}%");
                }
            })
            ->orderBy($sort_field, $sort_by);

        $collect = $payment_methods->get()->toArray();
        $total_records = collect($collect)->count();

        $results = $payment_methods->skip($offset)->take($rows)->get()->toArray();

        return [
            'payment_methods' => $results,
            'total_records' => $total_records,
            'message' => 'Success get payment methods'
        ];
    }

    public function setSortField($sort_field): string
    {
        if ($sort_field != null && $sort_field != 'undefined' && $sort_field != '') {
            switch ($sort_field) {
                case 'name':
                    return 'name';
                default:
                    return 'created_at';
            }
        }

        return 'created_at';

    }

    public function store($request_payload): PaymentMethod
    {
        $payment_method = new PaymentMethod();
        $payment_method->uuid = Uuid::generate(4)->string;
        $payment_method->user_id = $request_payload->user_id;
        $payment_method->name = $request_payload->name;
        $payment_method->is_active = $request_payload->is_active;
        $payment_method->save();

        return $payment_method;
    }

    public function show($uuid, $user_id)
    {
        return DB::table('payment_methods')
            ->select(
                'uuid',
                'name',
                'is_active'
            )
            ->where([
                'uuid' => $uuid,
                'user_id' => $user_id
            ])
            ->whereNull('deleted_at')
            ->first();
    }

    public function update($request_payload)
    {
        return PaymentMethod::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->update([
            'name' => $request_payload->name,
            'is_active' => $request_payload->is_active,
        ]);
    }

    public function destroy($request_payload)
    {
        return PaymentMethod::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->delete();
    }
}
