<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Products\ProductService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ProductService $service
     * @return JsonResponse
     */
    public function getAll(Request $request, ProductService $service): JsonResponse
    {
        $request_payload = $request->all();
        $request_payload['user_id'] = auth()->user()['uuid'];
        $response = $service->getAll($request_payload);

        return response()->json([
            'products' => $response['products'],
            'total_records' => $response['total_records'],
            'message' => 'Success get products',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request, ProductService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'sku' => 'required',
            'description' => 'required',
            'is_stock_tracked' => 'required',
            'is_sellable' => 'required',
            'sales_price' => 'required',
            'purchase_price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $request_payload = $request->all();
            $request_payload['user_id'] = auth()->user()['uuid'];
            $service->store($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Product has been created',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $uuid
     * @param ProductService $service
     * @return JsonResponse
     */
    public function show($uuid, ProductService $service): JsonResponse
    {
        $response = $service->show(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

        return response()->json([
            'product' => $response,
            'message' => 'Success get product',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $uuid
     * @param ProductService $service
     * @return JsonResponse
     */
    public function update(Request $request, $uuid, ProductService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'sku' => 'required',
            'description' => 'required',
            'is_stock_tracked' => 'required',
            'is_sellable' => 'required',
            'sales_price' => 'required',
            'purchase_price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request_payload = $request->all();
        $request_payload['uuid'] = $uuid;
        $request_payload['user_id'] = auth()->user()['uuid'];

        $find_by_uuid = $service->show($request_payload);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->update($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Product has been updated',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $uuid
     * @param ProductService $service
     * @return JsonResponse
     */
    public function destroy($uuid, ProductService $service): JsonResponse
    {
        $find_by_uuid = $service->show(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->destroy(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

            DB::commit();

            return response()->json([
                'message' => 'Product has been deleted',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
