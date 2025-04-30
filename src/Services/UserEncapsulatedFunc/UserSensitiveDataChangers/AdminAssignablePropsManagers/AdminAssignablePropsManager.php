<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\AdminAssignablePropsManagers;

use Illuminate\Database\Eloquent\Model;
use PixelApp\Models\Interfaces\BelongsToBranch;
use PixelApp\Models\Interfaces\BelongsToDepartment;
use PixelApp\Models\Interfaces\MustHaveRole;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasAdminAssignableProps;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

class AdminAssignablePropsManager
{ 
    protected ?self $instance  = null;
    protected static array $tempCustomPropChangers = [];
    protected  array $propChangers = [];

    
    public static function Singleton() : self
    {
        if(!static::$instance)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }
   
    public static function registerCustomPropChangerForModelClass(string $modelClass , string $propChangerClass)
    {
         if(array_key_exists($modelClass , static::$tempCustomPropChangers))
         {
            static::$tempCustomPropChangers[$modelClass][] = $propChangerClass;

         }else
         {
            static::$tempCustomPropChangers[$modelClass] = [ $propChangerClass ];
         } 
       }

    protected function registerModelPropChanger(string $modelClass , UserSensitivePropChanger $propChanger)
    {
        $this->propChangers[$modelClass][$propChanger->getPropName()] =  $propChanger; 
    }

    protected function initPropChanger(string $propChangerClass ) : UserSensitivePropChanger
    {
        if(is_subclass_of($propChangerClass , UserSensitivePropChanger::class))
        {
            return new $propChangerClass();
        }

        //for development only
        dd($propChangerClass . " class must be child type of " . UserSensitivePropChanger::class);
    }
    
    protected function initPropChangers(array $propChangerClasses) : array
    {
        return array_map(function($class)
               {
                   return $this->initPropChanger($class);

               } , $propChangerClasses);
    }

    protected function getModelCustomPropChangers(Model $model) : array
    {
        $modelClass = get_class($model);
        if(!array_key_exists($modelClass , static::$tempCustomPropChangers))
        {
            return [];
        }

        $propChangerClass = static::$tempCustomPropChangers[$modelClass];
        return $this->initPropChangers($propChangerClass);
    }


    protected function registerCustomPropChangers(Model $model) : void
    {
        $modelClass = get_class($model);  
        foreach($this->getModelCustomPropChangers($model) as $propChanger)
        {
            $this->registerModelPropChanger( $modelClass  , $propChanger);
        }
    }
 
    protected function registerDepartmentPropChanger(Model $model) : self
    {
        if($model instanceof BelongsToDepartment)
        {
            $modelClass =  get_class($model);
            $propChanger = $model->getDepartmentPropChanger();

            $this->registerModelPropChanger($modelClass , $propChanger);
        }

        return $this;
    }

    protected function registerBranchPropChanger(Model $model) : self
    {
        if($model instanceof BelongsToBranch)
        {
            $modelClass =  get_class($model);
            $propChanger = $model->getBranchPropChanger();

            $this->registerModelPropChanger($modelClass , $propChanger);
        }

        return $this;
    }

    protected function registerRolePropChanger(Model $model) : self
    {
        if($model instanceof MustHaveRole)
        {
            $modelClass =  get_class($model);
            $propChanger = $model->getRolePropChanger();

            $this->registerModelPropChanger($modelClass , $propChanger);
        }

        return $this;
    }
    
    protected function registerDefaultPropChangers(Model $model) : void
    {
        $this->registerRolePropChanger($model)
             ->registerBranchPropChanger($model)
             ->registerDepartmentPropChanger($model);
    }

    protected function registerModelToPropChangersArray(Model $model ) : void
    {
        //just a default value
        $this->propChangers[get_class($model)] = [];
    }

    protected function registerPropChangersForModel(Model $model) : array
    {
        
        $this->registerModelToPropChangersArray($model);

        if($model instanceof HasAdminAssignableProps)
        {
            $this->registerDefaultPropChangers($model);
            $this->registerCustomPropChangers($model);
        }

        return $this->getRegisteredPropChangersForModelClass(get_class($model));
    }

    protected function initModel(string $modelClass) : Model
    {
        if(is_subclass_of($modelClass , Model::class))
        {
            return new $modelClass();
        }

        dd($modelClass . " must be a child type of " . Model::class . " !");
    }

    protected function getRegisteredPropChangersForModelClass(string $modelClass) : ?array
    {
        return $this->propChangers[$modelClass] ?? null;
    }

    public function getSensitivePropChangersForClass(string $modelClass) : array
    {
        if($propChangers = $this->getRegisteredPropChangersForModelClass($modelClass))
        {
            return $propChangers;
        }

        $newModel = $this->initModel($modelClass);

        return $this->registerPropChangersForModel($newModel);
    }
    
    public function getSensitivePropChangersForModel(Model $model) : array
    {
        if($propChangers = $this->getRegisteredPropChangersForModelClass(get_class($model)))
        {
            return $propChangers;
        }

        return $this->registerPropChangersForModel($model);
    }
}