<?php

namespace  PixelApp\Services\UsersManagement\IndexingServices;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;  
use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager; 
use PixelApp\Services\CoreServices\ModelIndexingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UsersManagement\SpatieAllowedFilters\UserTypeAllowedFilters;
use PixelApp\Services\UsersManagement\SpatieAllowedFilters\UserTypeIndexingAllowedFilters;
use PixelApp\Services\UsersManagement\Statistics\UsersList\UsersListStatisticsBuilder; 

class UserTypeIndexingService extends ModelIndexingService
{

    public function __construct()
    {
       BasePolicy::check('readEmployees', $this->getModelClass());
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
  
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(UserTypeIndexingAllowedFilters::getFilters());
    }

    protected function eagerLoadRelations() : void
    {
        $this->query->with(['profile', 'profile.country', 'role', 'department'
        //,'branch'
        ]);
    }
    protected function setCustomScopes() : void
    {
        $this->query->datesFiltering()->activeUsers()->customOrdering('accepted_at', 'desc');
    }
  
    protected function getStatisitcsBuilder() : string
    {
        return PixelServiceManager::getServiceForServiceBaseType(UsersListStatisticsBuilder::class);
    }

    protected function getStatistics() : array
    {
        $statisticsBuilderClass = $this->getStatisitcsBuilder();
        return (new $statisticsBuilderClass())->getStatistics();
    }

    protected function respond($data)
    {    
        $statistics = $this->getStatistics();
        return Response::success(['list' => $data, 'statistics' => $statistics]);
    }
   
}
