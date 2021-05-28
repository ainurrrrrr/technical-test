<?php

namespace App\Services;

use stdClass;
use Illuminate\Support\Collection;
use App\BusinessLogic\BusinessLogic;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Service
{
    protected $logic = null;

    public function setLogicVersion($logic)
    {
        $this->logic = $logic ?: null;
    }

    protected function business($scope, $objectives): Collection
    {
        $results = collect();
        $scope = json_decode(json_encode($scope));

        /**
         * @var BusinessLogic $objective
         */
        foreach ($objectives as $objectiveString) {
            $realObjectiveString = $objectiveString;

            if (!empty($this->logic)) {
                $objectiveMapping = explode('\\', $objectiveString);
                $className = $objectiveMapping[count($objectiveMapping) - 1];
                $objectiveMapping[count($objectiveMapping) - 1] = $this->logic;
                $objectiveMapping[] = $className;
                $newObjectiveString = implode('\\', $objectiveMapping);

                if (class_exists($newObjectiveString)) {
                    $objectiveString = $newObjectiveString;
                }
            }

            if (!class_exists($objectiveString)) {
                throw  new UnprocessableEntityHttpException('class "' . $objectiveString . '" does not exists!');
            }

            $scope = $scope ?: new stdClass();
            $objective = new $objectiveString($scope);
            $currentResultRun = $objective->run();
            $scope = $objective->getScopes();
            $results->put($realObjectiveString, $currentResultRun);
        }

        return $results;
    }
}
