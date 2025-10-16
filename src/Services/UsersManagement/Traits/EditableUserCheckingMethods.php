<?php

namespace PixelApp\Services\UsersManagement\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Models\UsersModule\PixelUser;

/**
 * @property Model $model
 */
trait EditableUserCheckingMethods
{ 
    protected function checkPreConditions() : void
    {
        $this->checkDefaultAdmin($this->model);
    }

    protected function checkDefaultAdmin(Model $user) : void
    {
        if(!$user instanceof PixelUser)
        {
            throw new Exception("The model wanted to be editable by this interface must be a PixelUser typed object !");
        }

        if(!$user->isEditableUser())
        {
            throw new Exception("Can't edit a default admin !");
        }
    }

}