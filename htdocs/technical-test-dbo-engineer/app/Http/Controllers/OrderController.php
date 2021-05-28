<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Orders\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
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
     * @param OrderService $service
     * @return JsonResponse
     */
    public function getAll(Request $request, OrderService $service): JsonResponse
    {
        $request_payload = $request->all();
        $request_payload['user_id'] = auth()->user()['uuid'];
        $response = $service->getAll($request_payload);

        return response()->json([
            'orders' => $response['orders'],
            'total_records' => $response['total_records'],
            'message' => 'Success get orders',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
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
    public function store(Request $request, OrderService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|string|between:2,100',
            'shipping_address' => 'required|string',
            'billing_address' => 'required|string',
            'phone_number' => 'required',
            'grand_total' => 'required',
            'items' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $request_payload = $request->all();
            $request_payload['user_id'] = auth()->user()['uuid'];
            $request_payload['status'] = 'waiting_confirmation';
            $request_payload['order_date'] = Carbon::now()->format('Y-m-d H:i:s');
            $service->store($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Order has been created',
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
     * @param OrderService $service
     * @return JsonResponse
     */
    public function show($uuid, OrderService $service): JsonResponse
    {
        $response = $service->show(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

        return response()->json([
            'order' => $response,
            'message' => 'Success get order',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return void
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $uuid
     * @param OrderService $service
     * @return JsonResponse
     */
    public function update(Request $request, $uuid, OrderService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request_payload = $request->all();
        $request_payload['uuid'] = $uuid;
        $request_payload['user_id'] = auth()->user()['uuid'];

        $find_by_uuid = $service->show($request_payload);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->update($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Order has been updated',
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
     * @param OrderService $service
     * @return JsonResponse
     */
    public function destroy($uuid, OrderService $service)
    {
        $find_by_uuid = $service->show(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::beginTransaction();

        try {
            $request_payload = [
                'uuid' => $uuid,
                'order_number' => $find_by_uuid->order_number,
                'user_id' => auth()->user()['uuid']
            ];
            $service->destroy($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Order has been deleted',
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
