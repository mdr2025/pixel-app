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
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Interfaces\HasUUID;
use PixelApp\Interfaces\TenancyInterfaces\NeedsCentralDataSync;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Traits\interfacesCommonMethods\EmailAuthenticatableMethods;
use PixelApp\Traits\interfacesCommonMethods\TenancyDataSyncHelperMethods;
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
    EmailAuthenticatable
    //,NeedsCentralDataSync
{
    use  Authenticatable, Authorizable, HasApiTokens, HasFactory, Notifiable, EmailAuthenticatableMethods, SoftDeletes;
    //use TenancyDataSyncHelperMethods;

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
    const UserDEFAULT_TYPE = "signup"; 

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
        'default_user' => 'boolean'
    ];

    protected $hidden = [
        'password',
        'pivot'
    ];

    public static function getStatusValue(int $statusIntValue): string
    {
        return static::UserStatusNames[$statusIntValue] ?? static::UserDefaultInitStatusValue;
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
 
    public function scopeActive($query)
    {
        $query->where('status', 'pending');
    }
    public function isDefaultUser(): bool
    {
        return $this->role_id == 1;
    }


    //    public function branch()
    //    {
    //        return $this->belongsTo(Branch::class);
    //    }
    //
    public function canSyncData(): bool
    {
        return $this->isDefaultUser() && tenant();
    }
    public function isEditableUser(): bool
    {
        return !$this->isDefaultUser(); // add the conditions you need to make this user editable frm users management module
    }
    public function getCentralAppModelClass(): string
    {
        return CompanyDefaultAdmin::class;
    }

    public function getCentralAppModelIdentifierKeyName(): string
    {
        return $this->getEmailColumnName();
    }

    /**
     * @return int|string
     * it is an alias for getOriginalIdentifierValue method
     */
    public function getCentralAppModelIdentifierOriginalValue(): int|string
    {
        return $this->getOriginalIdentifierValue();
    }

    public function getSyncedAttributeNames(): array
    {
        /**
         * Here we can return any field we want ... there is no need to return the fields those are exist in fillables
         * because we are here customize the data will be saved in database ... it is not data coming from the request
         */
        return [
            $this->getEmailColumnName(),
            $this->getEmailVerificationDateColumnName(),
            $this->getEmailVerificationTokenColumnName(),
            'first_name',
            'last_name',
            'name',
            'password',
            'mobile',
        ];
    }
}
