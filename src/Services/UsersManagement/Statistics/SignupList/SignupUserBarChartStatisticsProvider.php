<?php

namespace  PixelApp\Services\UsersManagement\Statistics\SignupList;
 

use DataResourceInstructors\OperationComponents\Columns\Column;
use DataResourceInstructors\OperationComponents\OperationConditions\WhereConditions\WhereConditionTypes\WhereConditionPrimaryTypes\AndWhereCondition;
use DataResourceInstructors\OperationContainers\OperationGroups\OperationGroup;
use PixelApp\Models\PixelModelManager;
use Statistics\StatisticsProviders\StatisticsProviderCommonTypes\ChartStatisticsProviders\DateGroupedChartStatisticsProviders\BarCountChartStatisticsProvider;

class SignupUserBarChartStatisticsProvider extends BarCountChartStatisticsProvider
{
    public function getModelClass(): string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function setUserTypeCondition(OperationGroup $operationGroup)
    {
        $userTypeCondition = AndWhereCondition::create(Column::create("user_type") , "signup");
        $operationGroup->where($userTypeCondition);
    }
    protected function getDateGroupedRowsCountOperationGroup(): OperationGroup
    {
        $operationGroup = parent::getDateGroupedRowsCountOperationGroup();
        $this->setUserTypeCondition($operationGroup);
        return $operationGroup;
    }
}
