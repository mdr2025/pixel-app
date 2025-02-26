<?php

namespace  PixelApp\Services\UsersManagement\Statistics\SignupList;

use Statistics\StatisticsBuilderBaseService\StatisticsBuilderBaseService;


class SignupUserStatisticsBuilder extends StatisticsBuilderBaseService
{
    protected function getStatisticsProviderTypeClasses(): array
    {
        return [
            SignupUserBarChartStatisticsProvider::class,
            SignupUserSmallBoxesStatisticsProvider::class,
        ];
    }
}
