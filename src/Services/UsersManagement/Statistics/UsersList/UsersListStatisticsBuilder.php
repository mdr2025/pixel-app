<?php

namespace  PixelApp\Services\UsersManagement\Statistics\UsersList;

use Statistics\StatisticsBuilderBaseService\StatisticsBuilderBaseService;

class UsersListStatisticsBuilder extends StatisticsBuilderBaseService
{
    protected function getStatisticsProviderTypeClasses(): array
    {
        return [
            UsersListBarChartStatisticsProvider::class,
            UsersListSmallBoxesStatisticsProvider::class
        ];
    }
}
