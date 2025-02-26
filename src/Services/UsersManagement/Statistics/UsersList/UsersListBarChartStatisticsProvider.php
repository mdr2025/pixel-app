<?php

namespace  PixelApp\Services\UsersManagement\Statistics\UsersList;
 
use DataResourceInstructors\OperationComponents\Columns\Column;
use DataResourceInstructors\OperationComponents\OperationConditions\WhereConditions\WhereConditionTypes\WhereConditionPrimaryTypes\AndWhereCondition;
use DataResourceInstructors\OperationContainers\OperationGroups\OperationGroup;
use PixelApp\Models\PixelModelManager;
use Statistics\StatisticsProviders\StatisticsProviderCommonTypes\ChartStatisticsProviders\DateGroupedChartStatisticsProviders\BarCountChartStatisticsProvider;

class UsersListBarChartStatisticsProvider extends BarCountChartStatisticsProvider
{
    public function getModelClass(): string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function setUserTypeCondition(OperationGroup $operationGroup)
    {
        $userTypeCondition = AndWhereCondition::create(Column::create('user_type'), 'user');
        $operationGroup->where($userTypeCondition);
    }

    /**
     * @return OperationGroup
     * Overriding on the parent method to add a condition
     */
    protected function getDateGroupedRowsCountOperationGroup(): OperationGroup
    {
        $operationGroup = parent::getDateGroupedRowsCountOperationGroup();
        $this->setUserTypeCondition($operationGroup);
        return $operationGroup;
    }
}
