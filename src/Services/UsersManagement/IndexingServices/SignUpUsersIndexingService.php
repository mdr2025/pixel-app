<?php

namespace  PixelApp\Services\UsersManagement\IndexingServices;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager; 
use PixelApp\Services\CoreServices\ModelIndexingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UsersManagement\SpatieAllowedFilters\SignUpUsersAllowedFilters;
use PixelApp\Services\UsersManagement\SpatieAllowedFilters\SignUpUsersIndexingAllowedFilters;
use PixelApp\Services\UsersManagement\Statistics\SignupList\SignupUserStatisticsBuilder;
 

class SignUpUsersIndexingService extends ModelIndexingService
{

    public function __construct()
    {
        BasePolicy::check('readSignUpList', $this->getModelClass());
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
  
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(SignUpUsersIndexingAllowedFilters::getFilters());
    }

    protected function eagerLoadRelations() : void
    {
        $this->query->with(['profile', 'profile.country', 'department']);
    }

    protected function setCustomScopes() : void
    {
        $this->query->activeSignup()->datesFiltering()->customOrdering();
    }

    protected function getRequest() : Request
    {
        return request();
    }

    protected function addVerificationFilter() : void
    {
        $request = $this->getRequest();

        if($request->input('filter.email_verified_at') === 'verified')
        {
            $this->query->whereNotNull('email_verified_at');

        }elseif($request->input('filter.email_verified_at') === 'not verified')
        {
            $this->query->whereNull('email_verified_at');
        } 
    }

    protected function initSpatieQueryBuilder(): void
    {
        parent::initSpatieQueryBuilder();
        $this->addVerificationFilter();
    }

    protected function getStatisitcsBuilder() : string
    {
        return PixelServiceManager::getServiceForServiceBaseType(SignupUserStatisticsBuilder::class);
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
