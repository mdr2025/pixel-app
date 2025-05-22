<?php

namespace PixelApp\Services\CompanyAccountServices\BaseServices\CompanyUpdateAdmin;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use JsonException; 
use PixelApp\Http\Requests\CompanyAccountRequests\CompanyDefaultAdminUpdatingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DefaultUserPopChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;


abstract class CompanyDefaultAdminChangingBaseService  
{
    use GeneralValidationMethods;
 
    protected ?UserRoleChanger $userRoleChanger = null; 
    protected ?DefaultUserPopChanger $defaultUserPopChanger = null;
    protected PixelUser $currentDefaultUserAdmin;
    protected PixelUser $newAdminUser; 

    public function __construct()
    { 
        $this->setCurrentDefaultUserAdmin(); 
    }

    abstract protected function fetchCurrentDefaultUserAdmin() : ?PixelUser;

    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( CompanyDefaultAdminUpdatingRequest::class );
    }
  
    protected function getCurrentDefaultUserAdmin(): PixelUser
    {
        return $this->currentDefaultUserAdmin;
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
 
    protected function setCurrentDefaultUserAdmin(): void
    {
        if(!$user = $this->fetchCurrentDefaultUserAdmin())
        {
            throw new JsonException("There is no default admin assigned to the company !");
        }

        $this->currentDefaultUserAdmin =  $user; 
    }
 
    protected function afterDBTransactionRolledBack() : void
    {
        return;
    }

    protected function rollBackDBTransaction() : void
    {
        DB::rollBack();
    }
 
    protected function afterDBTransactionCommited() : void
    {
        return;
    }

    protected function commitDBTransaction() : void
    {
        DB::commit();
    }
   
    //default prop changing methods - start of part
    
    protected function initDefaultUserPopChanger() : DefaultUserPopChanger
    {
        if(!$this->defaultUserPopChanger)
        {
            $this->defaultUserPopChanger = new DefaultUserPopChanger();
        }
        return $this->defaultUserPopChanger;
    }

    protected function markOldAdminUserAsNonDefaultUser(PixelUser $user) : void
    {
        $this->initDefaultUserPopChanger()->setAuthenticatable($user)->convertToNonDefaultUser()->changeAuthenticatableProp();
    }

    protected function markNewAdminUserAsDefaultUser() : void
    {
        $this->initDefaultUserPopChanger()->setAuthenticatable($this->newAdminUser)->convertToDefaultUser()->changeAuthenticatableProp();
    }

    //default prop changing methods - end of part

    //role assigning changing methods - start of part
     
    protected function initUserRoleChanger() : UserRoleChanger
    {
        if(!$this->userRoleChanger)
        {
            $this->userRoleChanger = new UserRoleChanger();
        }
        return $this->userRoleChanger;
    }

    protected function assignOldAdminUsernewRole(PixelUser $user)
    {
        $this->initUserRoleChanger()->setAuthenticatable($user)->setData($this->data)->changeAuthenticatableProp();
    }

    protected function assignNewAdminUserAsSuperAdmin() : void
    {
        $this->initUserRoleChanger()->assignAsSuperAdmin($this->newAdminUser) ;
    }
    //role assigning changing methods - end of part

    protected function fetchNewAdminUser() : ?PixelUser
    {
        return $this->getUserModelClass()::findOrFail($this->data["user_id"]);
    }
    
    protected function setNewDefaultAdminUser() : void
    { 
        $this->newAdminUser = $this->fetchNewAdminUser();
    }

    protected function updateNewAdminRole(): self
    {
        $this->setNewDefaultAdminUser() ;
        
        $this->assignNewAdminUserAsSuperAdmin();

        $this->markNewAdminUserAsDefaultUser();

        $this->newAdminUser->save();

        return $this;
    }

    /**
     * ask later 
     * if the the request user id == user id
     * it will not be changed but the new admnin will be assigned as a default admin also
     * there will be may super admins and many defualt admins ... it is not implemented for now but ask later
     */
    protected function updatePreviousAdminRole(): self
    {
        $user = $this->getCurrentDefaultUserAdmin();
        
        $this->assignOldAdminUsernewRole($user);

        $this->markOldAdminUserAsNonDefaultUser($user);

        $user->save();
        return $this;
    }
  
    protected function switchAdminsRoles() : self
    {
        return $this->updatePreviousAdminRole()->updateNewAdminRole();
    }

    protected function beginDBtransaction() : void
    {
        DB::beginTransaction();
    }

    protected function isAdminAssigningHimSelf() : bool
    {  
        return $this->getCurrentDefaultUserAdmin()->id == $this->data["user_id"];
    }
    
    /**
     * @throws  JsonException
     */
    protected function checkActionImplementerUserPermissions(): self
    {
        /** @var PixelUser $user  */
        $user = auth()->user();
        if (!$user->isDefaultUser()) 
        {
            throw new JsonException("you don't have the permissions required to change the system 's default admin email");
        }

        return $this;
    }

    protected function checkActionImplementerUser() : self
    {
        $this->checkActionImplementerUserPermissions();

        if($this->isAdminAssigningHimSelf())
        {
            throw new JsonException("The selected user admin is already the default admin !");
        }

        return $this;
    }

    public function update()
    {
        $this->initValidator()->validateRequest()->setRequestData();

        $this->checkActionImplementerUser(); 

        try {

            $this->beginDBtransaction();
            $this->switchAdminsRoles();
            $this->commitDBTransaction();

            $this->afterDBTransactionCommited();
            
            return Response::success([] , ["message" => "Company Admin Has Been Updated"]);

        } catch (\Throwable $e)
        {
            $this->rollBackDBTransaction();

            $this->afterDBTransactionRolledBack();

            return REsponse::error($e->getMessage());
        }
    }

    
}
