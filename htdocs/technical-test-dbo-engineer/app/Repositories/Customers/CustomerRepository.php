<?php namespace App\Repositories\Customers;

use App\Customer;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{

    public function GetAllCustomer($request_payload): array
    {
        $user_id = $request_payload->user_id;
        $offset = $request_payload->first;
        $rows = $request_payload->rows;
        $filters = $request_payload->filters;
        $sort_by = ($request_payload->sortOrder == 1) ? 'asc' : 'desc';
        $sort_field = $this->setSortField($request_payload->sortField);

        $customers = DB::table('customers')
            ->select(
                'uuid',
                'email',
                'user_id',
                'name',
                'address',
                'phone_number'
            )
            ->where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($filters) {
                if (isset($filters->global)) {
                    $search = $filters->global;
                    $query->OrWhere('name', 'like', "%{$search}%")
                        ->OrWhere('email', 'like', "%{$search}%")
                        ->OrWhere('address', 'like', "%{$search}%")
                        ->OrWhere('phone_number', 'like', "%{$search}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->name)) {
                    $name = $filters->name;
                    $query->OrWhere('name', 'like', "%{$name}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->email)) {
                    $email = $filters->email;
                    $query->OrWhere('email', 'like', "%{$email}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->address)) {
                    $address = $filters->address;
                    $query->OrWhere('address', 'like', "%{$address}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->phone_number)) {
                    $phone_number = $filters->phone_number;
                    $query->OrWhere('phone_number', 'like', "%{$phone_number}%");
                }
            })
            ->orderBy($sort_field, $sort_by);

        $collect = $customers->get()->toArray();
        $total_records = collect($collect)->count();

        $results = $customers->skip($offset)->take($rows)->get()->toArray();

        return [
            'customers' => $results,
            'total_records' => $total_records,
            'message' => 'Success get customers'
        ];
    }

    public function setSortField($sort_field): string
    {
        if ($sort_field != null && $sort_field != 'undefined' && $sort_field != '') {
            switch ($sort_field) {
                case 'name':
                    return 'name';
                case 'email':
                    return 'email';
                case 'address':
                    return 'address';
                case 'phone_number':
                    return 'phone_number';
                default:
                    return 'created_at';
            }
        }

        return 'created_at';

    }

    public function store($request_payload): Customer
    {
        $customer = new Customer();
        $customer->uuid = Uuid::generate(4)->string;
        $customer->user_id = $request_payload->user_id;
        $customer->name = $request_payload->name;
        $customer->email = $request_payload->email;
        $customer->address = $request_payload->address;
        $customer->phone_number = $request_payload->phone_number;
        $customer->save();

        return $customer;
    }

    public function show($uuid, $user_id)
    {
        return DB::table('customers')
            ->select(
                'uuid',
                'email',
                'user_id',
                'name',
                'address',
                'phone_number'
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
        return Customer::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->update([
            'name' => $request_payload->name,
            'email' => $request_payload->email,
            'address' => $request_payload->address,
            'phone_number' => $request_payload->phone_number,
        ]);
    }

    public function destroy($request_payload)
    {
        return Customer::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->delete();
    }
}
