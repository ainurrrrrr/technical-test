<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Uoms\UomService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UomController extends Controller
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
     * @param UomService $service
     * @return JsonResponse
     */
    public function getAll(Request $request, UomService $service): JsonResponse
    {
        $request_payload = $request->all();
        $response = $service->getAll($request_payload);

        return response()->json([
            'uoms' => $response['uoms'],
            'total_records' => $response['total_records'],
            'message' => 'Success get uoms',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, UomService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $request_payload = $request->all();
            $service->store($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Uom has been created',
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
     * @param UomService $service
     * @return JsonResponse
     */
    public function show($uuid, UomService $service): JsonResponse
    {
        $response = $service->show(['uuid' => $uuid]);

        return response()->json([
            'uom' => $response,
            'message' => 'Success get uom',
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
     * @param UomService $service
     * @return JsonResponse
     */
    public function update(Request $request, $uuid, UomService $service)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request_payload = $request->all();
        $request_payload['uuid'] = $uuid;

        $find_by_uuid = $service->show($request_payload);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Uom not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->update($request_payload);

            DB::commit();

            return response()->json([
                'message' => 'Uom has been updated',
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
     * @param UomService $service
     * @return JsonResponse
     */
    public function destroy($uuid, UomService $service): JsonResponse
    {
        $find_by_uuid = $service->show(['uuid' => $uuid]);

        if (empty($find_by_uuid)) {
            return response()->json(['message' => 'Uom not found'], 404);
        }

        DB::beginTransaction();

        try {
            $service->destroy(['uuid' => $uuid]);

            DB::commit();

            return response()->json([
                'message' => 'Uom has been deleted',
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
