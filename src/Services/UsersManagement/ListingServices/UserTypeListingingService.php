<?php

namespace PixelApp\Services\UsersManagement\ListingServices;
 
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\UsersListResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department; 
use PixelApp\Services\CoreServices\ModelListingService;
use PixelApp\Services\UsersManagement\SpatieAllowedFilters\UserTypeListingAllowedFilters;

class UserTypeListingingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    } 
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(UserTypeListingAllowedFilters::getFilters());
    }

    protected function getListingResource() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(UsersListResource::class);
    }

    protected function getSelectedColumns() : array
    {
        return ["id", "name"];
    }

    protected function setCustomScopes() : void
    {
        $this->query->activeUsers()->customOrdering('created_at', 'desc');
    }
  
    protected function eagerLoadRelations() : void
    {
        $this->query->with(["profile:user_id,logo"]);
    }

    protected function initSpatieQueryBuilder(): void
    {
        parent::initSpatieQueryBuilder();
        $this->eagerLoadRelations();
    }

    protected function respond($data)
    { 
        $resourceClass = $this->getListingResource();
        
        return response()->json([
                                    "data" => $resourceClass::collection($data)
                                ]); 
    }
   
}
