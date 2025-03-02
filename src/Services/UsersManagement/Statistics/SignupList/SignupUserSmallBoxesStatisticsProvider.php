<?php

namespace  PixelApp\Services\UsersManagement\Statistics\SignupList;

use PixelApp\Exceptions\JsonException;
use DataResourceInstructors\OperationComponents\Columns\Column;
use DataResourceInstructors\OperationComponents\OperationConditions\WhereConditions\WhereConditionTypes\WhereConditionPrimaryTypes\AndWhereCondition;
use DataResourceInstructors\OperationContainers\OperationGroups\OperationGroup;
use Statistics\Interfaces\StatisticsProvidersInterfaces\NeedsAdditionalAdvancedOperations;
use Statistics\OperationsManagement\Operations\CommonOperationFactories\CountingAddedInDateRangeOperationFactory;
use Statistics\OperationsManagement\Operations\CommonOperationFactories\CountingAllRowsUntilEndDateOperationFactory;
use Statistics\StatisticsProviders\StatisticsProviderCommonTypes\SmallBoxesStatisticsProvider;

class SignupUserSmallBoxesStatisticsProvider extends SmallBoxesStatisticsProvider implements NeedsAdditionalAdvancedOperations
{
    protected function setUserTypeCondition(OperationGroup $operationGroup)
    {
        $userTypeCondition = AndWhereCondition::create(Column::create("user_type") , "signup");
        $operationGroup->where($userTypeCondition);
    }
    /**
     * @return OperationGroup
     * @throws JsonException
     */
    protected function getAddedRowsCountingOperation(): OperationGroup
    {
        $operationGroup = (new CountingAddedInDateRangeOperationFactory())->setTableName("users")->make();
        $this->setUserTypeCondition($operationGroup);
        return $operationGroup;
    }

    /**
     * @return OperationGroup
     * @throws JsonException
     */
    protected function getAllRowsCountingOperation(): OperationGroup
    {
        $operationGroup = (new CountingAllRowsUntilEndDateOperationFactory($this->dateProcessor))->setTableName("users")->make();
        $this->setUserTypeCondition($operationGroup);
        return $operationGroup;
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function getAdditionalAdvancedOperations(): array
    {
        return [
            $this->getAddedRowsCountingOperation(),
            $this->getAllRowsCountingOperation()
        ];
    }
}
