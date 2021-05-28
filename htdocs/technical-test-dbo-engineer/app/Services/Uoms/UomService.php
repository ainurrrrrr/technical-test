<?php

namespace App\Services\Uoms;

use App\Services\Service;
use App\BusinessLogic\Uoms\GetAllUomBusinessLogic;
use App\BusinessLogic\Uoms\StoreUomBusinessLogic;
use App\BusinessLogic\Uoms\ShowUomBusinessLogic;
use App\BusinessLogic\Uoms\UpdateUomBusinessLogic;
use App\BusinessLogic\Uoms\DestroyUomBusinessLogic;

class UomService extends Service
{
    public function getAll($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            GetAllUomBusinessLogic::class,
        ]);

        return $responses->get(GetAllUomBusinessLogic::class);
    }

    public function store($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            StoreUomBusinessLogic::class,
        ]);

        return $responses->get(StoreUomBusinessLogic::class);
    }

    public function show($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            ShowUomBusinessLogic::class,
        ]);

        return $responses->get(ShowUomBusinessLogic::class);
    }

    public function update($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            UpdateUomBusinessLogic::class,
        ]);

        return $responses->get(UpdateUomBusinessLogic::class);
    }

    public function destroy($request_payload)
    {
        $scope = $request_payload;

        $responses = $this->business($scope, [
            DestroyUomBusinessLogic::class,
        ]);

        return $responses->get(DestroyUomBusinessLogic::class);
    }
}
