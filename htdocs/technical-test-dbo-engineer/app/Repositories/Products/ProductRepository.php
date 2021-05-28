<?php namespace App\Repositories\Products;

use App\Product;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class ProductRepository
{

    public function GetAllProduct($request_payload): array
    {
        $user_id = $request_payload->user_id;
        $offset = $request_payload->first;
        $rows = $request_payload->rows;
        $filters = $request_payload->filters;
        $sort_by = ($request_payload->sortOrder == 1) ? 'asc' : 'desc';
        $sort_field = $this->setSortField($request_payload->sortField);

        $products = DB::table('products')
            ->select(
                'uuid',
                'user_id',
                'name',
                'sku',
                'uom_id',
                'description',
                'is_stock_tracked',
                'is_sellable',
                'sales_price',
                'purchase_price'
            )
            ->where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($filters) {
                if (isset($filters->global)) {
                    $search = $filters->global;
                    $query->OrWhere('name', 'like', "%{$search}%")
                        ->OrWhere('sku', 'like', "%{$search}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->name)) {
                    $name = $filters->name;
                    $query->OrWhere('name', 'like', "%{$name}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->sku)) {
                    $sku = $filters->sku;
                    $query->OrWhere('sku', 'like', "%{$sku}%");
                }
            })
            ->orderBy($sort_field, $sort_by);

        $collect = $products->get()->toArray();
        $total_records = collect($collect)->count();

        $results = $products->skip($offset)->take($rows)->get()->toArray();

        return [
            'products' => $results,
            'total_records' => $total_records,
            'message' => 'Success get products'
        ];
    }

    public function setSortField($sort_field): string
    {
        if ($sort_field != null && $sort_field != 'undefined' && $sort_field != '') {
            switch ($sort_field) {
                case 'name':
                    return 'name';
                case 'sku':
                    return 'sku';
                default:
                    return 'created_at';
            }
        }

        return 'created_at';

    }

    public function store($request_payload): Product
    {
        $product = new Product();
        $product->uuid = Uuid::generate(4)->string;
        $product->user_id = $request_payload->user_id;
        $product->name = $request_payload->name;
        $product->sku = $request_payload->sku;
        $product->uom_id = $request_payload->uom_id;
        $product->description = $request_payload->description;
        $product->is_stock_tracked = $request_payload->is_stock_tracked;
        $product->is_sellable = $request_payload->is_sellable;
        $product->sales_price = $request_payload->sales_price;
        $product->purchase_price = $request_payload->purchase_price;
        $product->save();

        return $product;
    }

    public function show($uuid, $user_id)
    {
        return DB::table('products')
            ->select(
                'uuid',
                'user_id',
                'name',
                'sku',
                'uom_id',
                'description',
                'is_stock_tracked',
                'is_sellable',
                'sales_price',
                'purchase_price'
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
        return Product::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->update([
            'name' => $request_payload->name,
            'sku' => $request_payload->sku,
            'uom_id' => $request_payload->uom_id,
            'description' => $request_payload->description,
            'is_stock_tracked' => $request_payload->is_stock_tracked,
            'is_sellable' => $request_payload->is_sellable,
            'sales_price' => $request_payload->sales_price,
            'purchase_price' => $request_payload->purchase_price,
        ]);
    }

    public function destroy($request_payload)
    {
        return Product::where([
            'uuid' => $request_payload->uuid,
            'user_id' => $request_payload->user_id
        ])->delete();
    }
}
