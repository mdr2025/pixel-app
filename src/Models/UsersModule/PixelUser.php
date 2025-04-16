<?php

namespace PixelApp\Models\UsersModule;
 
use PixelApp\Models\PixelBaseModel ;  
use AuthorizationManagement\Interfaces\HasAuthorizablePermissions;
use CRUDServices\CRUDComponents\CRUDRelationshipComponents\OwnedRelationshipComponent;
use CRUDServices\Interfaces\OwnsRelationships;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Interfaces\HasUUID;
use PixelApp\Interfaces\TenancyInterfaces\CanSyncData;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\TenancyDataSyncingEventFactories\UsersModule\TenantUserDataSyncingEventFactory;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\StatusChangeableAccount;
use PixelApp\Traits\interfacesCommonMethods\EmailAuthenticatableMethods;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;
use Spatie\Permission\Models\Role;

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
    CanSyncData
{
    use  Authenticatable, Authorizable, HasApiTokens, HasFactory, Notifiable, EmailAuthenticatableMethods, SoftDeletes;
  
    public static $snakeAttributes = false;

    const USER_STATUS = ["active", "inactive"]; // Database column values
    const SIGN_UP_STATUS = ["pending", "rejected"]; // Database column values
    const SIGN_UP_STATUS_CHANGING_VALUES = ["active", "rejected"]; // Allowed values to use during status changing
    const USER_STATUS_CHANGING_VALUES = ["active", "inactive"]; // Allowed values to use during status changing
    const UserDefaultInitStatusValue = "pending";
    const UserStatusNames = [
        "pending",
        "active",
        "inactive",
        "rejected"
    ];
    const UserAllowedTypes = ["user", "signup"];
    const UserDefaultType = "signup"; 

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

    protected $guarded = ['accepted_at', 'status', 'user_type', 'role_id' , 'default_user'];

    protected $casts = [
        'role_id' => 'integer',
        'default_user' => 'boolean',
        'department_id' => 'integer',
        'branch_id' => 'integer',
        'accepted_at' => 'datetime'
    ];

    protected $hidden = [
        'password',
        'pivot'
    ];

    public static function getStatusValue(int $statusIntValue): string
    {
        return static::UserStatusNames[$statusIntValue] ?? static::UserDefaultInitStatusValue;
    }

    public function getSignUpAccountStatusChangableValues() : array
    {
        return ["active", "rejected"];
    }
    public function getAcceptedAccountStatusChangableValues() : array
    {
        return ["active", "inactive"];
    }

    public function isSystemMemberAccount()  :bool
    {
        return $this->user_type == "user";
    }

    public function isSignUpAccount() : bool
    {
        return $this->user_type == "signup";
    }

    public function getApprovingStatusValue()  :string
    {
        return "active";
    }

    public function getAccountApprovingProps()
    { 
        return [
            "accepted_at" => now(),
            "user_type" => "user"
        ];
    }

    public function getDefaultStatusValue() : string
    {
        return "pending";
    }

    public function generateName(): void
    {
        $this->name = "{$this->first_name} {$this->last_name}";
    }
    //Relationships part - start
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, "user_id", "id");
    }

    public function signature()
    {
        return $this->hasOne(Signature::class, 'user_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, "role_id", "id");
    }

    public function permissions(): array
    {
        return $this->role?->permissions->pluck("name")->toArray() ?? [];
    }

    public function HasPermission(string $permissionToCheck): bool
    {
        $userPermissions = $this->permissions();
        return in_array($permissionToCheck, $userPermissions);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(UserAttachment::class, 'user_id');
    }
    public function getOwnedRelationships(): array
    {
        return [
            OwnedRelationshipComponent::create("profile", "user_id")->setUpdatingConditionColumns(["user_id"])->appendForignKeyToRequestData(),
            OwnedRelationshipComponent::create("attachments", "user_id")->setUpdatingConditionColumns(['id', "user_id"])
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }


    public function scopeNotSuperAdmin($query)
    {
        $query->where('role_id', '!=', 1);
    }

    public function scopeActiveUsers($query)
    {
        $query->whereIn('status', $this::USER_STATUS)->where('user_type', 'user');
    }

    public function scopeActiveSignup($query)
    {
        $query->whereIn('status', $this::SIGN_UP_STATUS)->where('user_type', 'signup');
    }
 
    public function scopeUser($query)
    {
        $query->where("user_type" , 'user');
    }

    public function scopeSignUpUser($query)
    {
        $query->where("user_type" , 'signup');
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active');
    }

    public function isDefaultUser(): bool
    {
        return $this->role_id == 1 || $this->default_user == 1;
    }


    //    public function branch()
    //    {
    //        return $this->belongsTo(Branch::class);
    //    }
    //

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
        return PixelTenancyManager::isItTenancySupportyerApp() 
               &&
               tenant()
               &&
               $this->isDefaultUser();
    }

    public function isEditableUser(): bool
    {
        return !$this->isDefaultUser(); // add the conditions you need to make this user editable frm users management module
    }

}
