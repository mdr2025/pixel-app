<?php

namespace PixelApp\Services\UsersManagement\ListingServices;
  
use PixelApp\Models\PixelModelManager; 
use PixelApp\Services\CoreServices\ModelListingService;
use PixelApp\Services\UsersManagement\SpatieAllowedFilters\UserTypeListingAllowedFilters;

class DefaultUsersListingService extends ModelListingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    } 
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(UserTypeListingAllowedFilters::getFilters());
    }
 
    protected function getSelectedColumns() : array
    {
        return ["id", "name", "email", "hashed_id"];
    }

    protected function setCustomScopes() : void
    {
        $this->query->notSuperAdmin()->activeUsers()->customOrdering('created_at', 'desc');
    }
  
    protected function eagerLoadRelations() : void
    {
        $this->query->with(['profile:user_id,logo']);
    }

    protected function initSpatieQueryBuilder(): void
    {
        parent::initSpatieQueryBuilder();
        $this->eagerLoadRelations();
    }

    protected function respond($data)
    {   
        return response()->json([
                                    "data" => $data->toArray()
                                ]); 
    }
   
}
