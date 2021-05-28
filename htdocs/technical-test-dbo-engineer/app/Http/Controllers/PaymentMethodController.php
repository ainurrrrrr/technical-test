<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\PaymentMethods\PaymentMethodService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param PaymentMethodService $service
     * @return JsonResponse
     */
    public function getAll(Request $request, PaymentMethodService $service): JsonResponse
    {
        $request_payload = $request->all();
        $request_payload['user_id'] = auth()->user()['uuid'];
        $response = $service->getAll($request_payload);

        return response()->json([
            'payment_methods' => $response['payment_methods'],
            'total_records' => $response['total_records'],
            'message' => 'Success get payment methods',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param PaymentMethodService $service
     * @return JsonResponse
     */
    public function store(Request $request, PaymentMethodService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'is_active' => 'required',
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
                'message' => 'Payment method has been created',
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
     * @param PaymentMethodService $service
     * @return JsonResponse
     */
    public function show($uuid, PaymentMethodService $service): JsonResponse
    {
        $response = $service->show(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

        return response()->json([
            'payment_method' => $response,
            'message' => 'Success get payment method',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
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
     * @param PaymentMethodService $service
     * @return JsonResponse
     */
    public function update(Request $request, $uuid, PaymentMethodService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request_payload = $request->all();
        $request_payload['uuid'] = $uuid;
        $request_payload['user_id'] = auth()->user()['uuid'];

        $find_by_uuid = $service->show($request_payload);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->update($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Payment method has been updated',
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
     * @param PaymentMethodService $service
     * @return JsonResponse
     */
    public function destroy($uuid, PaymentMethodService $service): JsonResponse
    {
        $find_by_uuid = $service->show(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->destroy(['uuid' => $uuid, 'user_id' => auth()->user()['uuid']]);

            DB::commit();

            return response()->json([
                'message' => 'Payment method has been deleted',
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
