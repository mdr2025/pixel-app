<?php

namespace PixelApp\Models\UsersModule;
 
use PixelApp\Models\PixelBaseModel ;  
use AuthorizationManagement\Interfaces\HasAuthorizablePermissions;
use CRUDServices\CRUDComponents\CRUDRelationshipComponents\OwnedRelationshipComponent;
use CRUDServices\CRUDComponents\CRUDRelationshipComponents\ParticipatingRelationshipComponent;
use CRUDServices\Interfaces\OwnsRelationships;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Database\Factories\UserModule\AcceptedUserFactory;
use PixelApp\Database\Factories\UserModule\SignUpUserFactory;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Interfaces\HasUUID;
use PixelApp\Interfaces\TenancyInterfaces\CanSyncData;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToBranch;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\MustHaveRole;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\TenancyDataSyncingEventFactories\UsersModule\TenantUserDataSyncingEventFactory;
use PixelApp\Models\Traits\OptionalRelationsTraits\BelongsToBranchMethods;
use PixelApp\Models\Traits\OptionalRelationsTraits\BelongsToDepartmentMethods;
use PixelApp\Models\Traits\OptionalRelationsTraits\MustHaveRoleMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasAdminAssignableProps;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\StatusChangeableAccount;
use PixelApp\Traits\interfacesCommonMethods\EmailAuthenticatableMethods;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;
use Spatie\Permission\Models\Role;
use PixelApp\Traits\Models\Scopes\BranchFilterScope;

class PixelUser extends PixelBaseModel
implements
    HasUUID,
    OwnsRelationships,
    HasAuthorizablePermissions,
    NeededFromChildes,
    AuthenticatableContract,
    AuthorizableContract,
    EmailAuthenticatable ,
    StatusChangeableAccount,
    CanSyncData,
    MustHaveRole,
    HasAdminAssignableProps
{
    use  Authenticatable,
         Authorizable,
         HasApiTokens,
         HasFactory,
         Notifiable,
         EmailAuthenticatableMethods,
         SoftDeletes ,
         MustHaveRoleMethods,
         BranchFilterScope,
         BelongsToBranchMethods,
         BelongsToDepartmentMethods;
  
    protected bool $fakedUsersStatus = false;


    // ========================================
    // CONSTANTS
    // ========================================

    /**
     * User Status Constants
     */
    public const ACTIVE_USER_STATUS = 'active';
    public const INACTIVE_USER_STATUS = 'inactive';
    public const PENDING_USER_STATUS = 'pending';
    public const REJECTED_SIGN_UP_STATUS = 'rejected';

    /**
     * User Type Constants
     */
    public const USER_TYPE_USER = 'user';
    public const USER_TYPE_SIGNUP = 'signup';

   

    /**
     * Status Arrays for Validation and Filtering
     */
    public const USER_STATUS = [
        self::ACTIVE_USER_STATUS,
        self::INACTIVE_USER_STATUS
    ];

    public const SIGN_UP_STATUS = [
        self::PENDING_USER_STATUS,
        self::REJECTED_SIGN_UP_STATUS
    ];

    public const SIGN_UP_STATUS_CHANGING_VALUES = [
        self::ACTIVE_USER_STATUS,
        self::REJECTED_SIGN_UP_STATUS
    ];

    public const USER_STATUS_CHANGING_VALUES = [
        self::ACTIVE_USER_STATUS,
        self::INACTIVE_USER_STATUS
    ];

    public const USER_STATUS_VALUES = [
        self::PENDING_USER_STATUS,
        self::ACTIVE_USER_STATUS,
        self::INACTIVE_USER_STATUS,
        self::REJECTED_SIGN_UP_STATUS
    ];

    /**
     * User Type Configuration
     */
    public const USER_ALLOWED_TYPES = [
        self::USER_TYPE_USER,
        self::USER_TYPE_SIGNUP
    ];

    public const USER_DEFAULT_TYPE = self::USER_TYPE_SIGNUP;
    // public const USER_DEFAULT_INIT_STATUS = 0;
    public const USER_DEFAULT_INIT_STATUS_VALUE = self::PENDING_USER_STATUS;

    // ========================================
    // MODEL PROPERTIES
    // ========================================

    protected $table = "users";
    protected $fillable = [
        'hashed_id',
        'email',
        'verification_token',
        'first_name',
        'last_name',
        'name',
        'full_name',
        'password',
        'mobile',
        'employee_id',
        
    ];

    protected $guarded = [
                            'accepted_at',
                            'status',
                            'user_type', 
                            'default_user'
                         ];

    protected $casts = [
        'default_user' => 'boolean', 
        'accepted_at' => 'datetime'
    ];

    protected $hidden = [
        'password',
        'pivot'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->handleOptionalRelationFields();
    }

    protected function handleOptionalRelationFields() : void
    {
        if(method_exists($this , 'appendDepartmentFields'))
        {
            $this->appendDepartmentFields();
        }

        
        if(method_exists($this , 'appendBranchFields'))
        {
            $this->appendBranchFields();
        }

        $this->appendRoleFileds();
    }

    
    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'can_edit',
        'can_change_email',
        'can_change_status'
    ];

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * Get status value by integer index
     */
    public static function getStatusValue(int $statusIntValue): string
    {
        return static::USER_STATUS_VALUES[$statusIntValue] ?? static::USER_DEFAULT_INIT_STATUS_VALUE;
    }


    public function getSignUpAccountStatusChangableValues() : array
    {
        return self::SIGN_UP_STATUS_CHANGING_VALUES;;
    }
    public function getAcceptedAccountStatusChangableValues() : array
    {
        return self::USER_STATUS_CHANGING_VALUES;;
    }

    public function isSystemMemberAccount()  :bool
    {
        return $this->user_type == self::USER_TYPE_USER;
    }

    public function isSignUpAccount() : bool
    {
        return $this->user_type == self::USER_TYPE_SIGNUP;
    }

    public function getApprovingStatusValue()  :string
    {
        return self::ACTIVE_USER_STATUS;
    }

    public function getRejectedStatusValue()  :string
    {
        return self::REJECTED_SIGN_UP_STATUS;
    }
    
    public function getAccountApprovingProps()
    { 
        return [
            "accepted_at" => now(),
            "user_type" => static::USER_TYPE_USER
        ];
    }

    public function getDefaultStatusValue() : string
    {
        return self::USER_DEFAULT_INIT_STATUS_VALUE;
    }

    public function generateName(): void
    {
        $this->name = "{$this->first_name} {$this->last_name}";
    }
    //Relationships part - start
    
    protected function getUserProfileModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(UserProfile::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne($this->getUserProfileModelClass(), "user_id", "id");
    }

    protected function getUserAttachmentModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(UserAttachment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany($this->getUserAttachmentModelClass(), 'user_id');
    }
 
    /**
     * Get the relationships that this model owns
     */
    public function getOwnedRelationships(): array
    {
        return [
            OwnedRelationshipComponent::create("profile", "user_id")
                ->setUpdatingConditionColumns(["user_id"])
                ->appendForignKeyToRequestData(),
            OwnedRelationshipComponent::create("attachments", "user_id")
                ->setUpdatingConditionColumns(['id', "user_id"])
        ];
    }

     // ========================================
    // PARTICIPATING RELATIONSHIPS
    // ========================================

    /**
     * Get the relationships where this model participates
     */
    public function getParticipatingRelationships(): array
    {
        $reltionComponents = [];

        if($this instanceof BelongsToBranch)
        {
            $reltionComponents[] = ParticipatingRelationshipComponent::create('accessibleBranches', $this->getBranchForeignKeyName());
        }
        return $reltionComponents;
    }

   /**
     * Scope to get only allowed users (active users, not signups)
     */
    public function scopeAllowedUsers($query): void
    {
        $query->whereIn('status', $this::USER_STATUS)
            ->where('user_type', $this::USER_TYPE_USER);

        $this->applyViewAsFilter($query);
    }

    
    /**
     * Scope to get only signup users (pending/rejected)
     */
    public function scopeAllowedSignUps($query): void
    {
        $query->whereIn('status', $this::SIGN_UP_STATUS)
              ->where('user_type', $this::USER_TYPE_SIGNUP);
    }

    /**
     * Scope to get only pending users
     */
    public function scopePendingUsers($query): void
    {
        $query->where('status', self::PENDING_USER_STATUS);
    }

    /**
     * Scope to get only active users
     */
    public function scopeActiveUsers($query): void
    {
        $query->where('status', self::ACTIVE_USER_STATUS);
    } 
 
    /**
     * Scope to filter by email verification status
     */
    public function scopeEmailVerified($query, ?string $status = null): Builder
    {
        return match ($status) {
            'verified' => $query->whereNotNull('email_verified_at'),
            'not verified', 'not_verified' => $query->whereNull('email_verified_at'),
            default => $query,
        };
    }

    public function scopeSignup($query)
    {
        $query->where('status', $this::USER_DEFAULT_INIT_STATUS_VALUE)
             ->where('user_type', $this::USER_TYPE_SIGNUP);
    }

    public function scopeActive($query)
    {
        $query->where('status', $this::ACTIVE_USER_STATUS );
    }
 
    public function getTenancyDataSyncingEvent() : ?TenancyDataSyncingEvent
    {
        if($this->canSyncData())
        {
            return (new TenantUserDataSyncingEventFactory($this))->createTenancyDataSyncingEvent();
        }

        return null; 
    }

    public function canSyncData(): bool
    {
        return PixelTenancyManager::isItTenancySupporterApp() 
               &&
               tenant()
               &&
               $this->isCreatedAsDefault();
    }

    public function scopeDefaultUser($query)
    {
        //default_user == 1 is enough ... but when it is set to 0 manually
        //we need another condition to fetch the default admin in system
        $query->where("default_user" , 1)->orWhere("role_id" , 1);
    }

    
    // ========================================
    // ACCESSORS
    // ========================================

    /**
     * Check if user can edit their profile
     */
    public function getCanEditAttribute(): bool
    {
        return $this->isEditableUser();
    }

    /**
     * Check if user can change their email
     */
    public function getCanChangeEmailAttribute(): bool
    {
        return $this->isEditableUser();
    }

    /**
     * Check if user can change their status
     */
    public function getCanChangeStatusAttribute(): bool
    {
        return $this->isSuperAdmin();
    }

    
    /**
     * Get the user's primary branch ID
     */
    public function getPrimaryBranchIdAttribute(): ?int
    {
        return $this->branch_id;
    }


    // ========================================
    // PRIVATE METHODS
    // ========================================

    /**
     * Apply view-as filter to queries
     */
    private function applyViewAsFilter(Builder $query): void
    {
        // Logic to be implemented later
    }


    /**
     * Check if user is a super admin not a user with another role
     */
    public function isSuperAdmin(): bool
    {
        return $this->role_id == 1;
    }

    public function isCreatedAsDefault(): bool
    {
        return $this->default_user == 1;
    }
     /**
     * Check if user can edit their profile
     */
    public function isEditableUser(): bool
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $canEditHimself = $this->id == auth()->id();

        return !$isSuperAdmin || ($isSuperAdmin && $canEditHimself);
    }
 
    public static function fakeAcceptedUsers() : void
    {
        static::$fakedUsersStatus = true;
    }
    
    public static function fakeSignUpUsers() : void
    {
        static::$fakedUsersStatus = false;
    }

    protected static function newFactory()
    {
        return static::$fakedUsersStatus ? 
               AcceptedUserFactory::new() :
               SignUpUserFactory::new();
    }

}
