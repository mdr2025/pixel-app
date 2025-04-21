<?php

namespace PixelApp\Services\UserEncapsulatedFunc\RegistrableUserHandlers;
  
use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Models\Interfaces\BelongsToBranch;
use PixelApp\Models\Interfaces\BelongsToDepartment;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers\VerificationPropsChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\BranchChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DefaultUserPopChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\StatusChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;

class RegistrableDefaultSuperAdminApprover
{
    protected PixelUser $user;
    protected EmailAuthenticatable | Model $notApprovedAdmin ;

    /**
     * @throws Exception
     */
    public function __construct(EmailAuthenticatable | Model $notApprovedAdmin)
    {
        $this->setEmailAuthenticatableNotApprovedAdmin($notApprovedAdmin)->initApprovedUser();
    }

    /**
     * @param EmailAuthenticatable|Model $notApprovedAdmin
     * @return $this
     * @throws Exception
     */
    public function setEmailAuthenticatableNotApprovedAdmin(EmailAuthenticatable | Model $notApprovedAdmin): self
    {
        if(!$notApprovedAdmin instanceof Model || !$notApprovedAdmin instanceof EmailAuthenticatable)
        {
            throw new Exception("notApprovedAdmin value must be a model type and must implement EmailAuthenticatable interface !");
        }
        $this->notApprovedAdmin = $notApprovedAdmin;
        return $this;
    }
    protected function showNotApprovedAdminAllAttributes() : void
    {
        $this->notApprovedAdmin->makeVisible( $this->notApprovedAdmin->getHidden() );
    }

    protected function getUSerModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function initApprovedUser() : self
    {
        $this->showNotApprovedAdminAllAttributes();
        $userModelClass = $this->getUSerModelClass();
        $this->user = new $userModelClass(  $this->notApprovedAdmin->toArray() );
        return $this;
    }

    /**
     * @return PixelUser
     */
    public function getUser(): PixelUser
    {
        return $this->user;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function convertToDefaultUser() : self
    {
        (new DefaultUserPopChanger())->setAuthenticatable($this->user)->convertToDefaultUser()->changeAuthenticatablePropOrFail();
        return  $this;
    }
    /**
     * @return self
     * @throws Exception
     */
    protected function verifyAdmin() : self
    {
        if($this->notApprovedAdmin->isVerified())
        {
            /**  @var VerificationPropsChanger $AuthenticatableVerificationPropsChanger  */
            $AuthenticatableVerificationPropsChanger =  new VerificationPropsChanger( $this->user );

            $AuthenticatableVerificationPropsChanger->verify()->changeAuthenticatablePropOrFail();
        }
        return $this;
    }
    /**
     * @throws Exception
     */
    protected function setAcceptedAdminStatus() : self
    {
        (new StatusChanger())->approve($this->user)->changeAuthenticatablePropOrFail();
        return $this;
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function setDefaultRole() : self
    {
        (new UserRoleChanger())->setAuthenticatable($this->user)
                               ->setNewActiveRole( RoleModel::findHighestRole() )
                               ->changeAuthenticatablePropOrFail();
        return $this;
    }
   
    protected function getDepartmentModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }
    /**
     * @return $this
     * @throws Exception
     * @todo later : must be implemented by the child system (must be a func to do extra somthings by child classes )
     */
    protected function setDefaultDepartment() : self
    {
        if($this->user instanceof BelongsToDepartment)
        { 
            $departmentClass = $this->getDepartmentModelClass(Department::class);
            (new DepartmentChanger())->setAuthenticatable( $this->user )
                                     ->setDepartment( $departmentClass::findTeamManagementDepartment() )
                                     ->changeAuthenticatablePropOrFail();
        }
        
        return $this;
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }
    /**
     * @return $this
     * @throws Exception
      * @todo later : must be implemented by the child system (must be a func to do extra somthings by child classes )
     */
    protected function setHeadQuarterBranch() : self
    { 
        if($this->user instanceof BelongsToBranch)
        {
            $branchClass = $this->getBranchModelClass();
            (new BranchChanger())->setAuthenticatable( $this->user ) 
                                 ->setBranch( $branchClass::findHeadquarter() )
                                 ->changeAuthenticatablePropOrFail(); 
        }
        return $this;
    }

    /**
     * @return PixelUser
     * @throws Exception
     */
    public function approveAdmin() : PixelUser
    {
        /**
         * operations on user
         */
        $this->setDefaultRole()->setDefaultDepartment() 
            ->setHeadQuarterBranch()->setAcceptedAdminStatus()
            ->verifyAdmin()->convertToDefaultUser();
 
        /**
         * returning the initialized and approved user
         */
        return $this->getUser();
    }
}
