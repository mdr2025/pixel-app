<?php

namespace PixelApp\Models\SystemConfigurationModels;

use CRUDServices\CRUDComponents\CRUDRelationshipComponents\OwnedRelationshipComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\Models\PixelBaseModel;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $default
 */
class Branch extends PixelBaseModel 
{
    use HasFactory;
    protected $table = "branches";
    const ROUTE_PARAMETER_NAME = "branch";
    const TYPE = ['main', 'child'];
    const DEFAULT_TYPE = 'child';

    protected $fillable = [
        'name',
        'status',
        'parent_id',
        'country_id',
        'type'
    ];
    protected $casts = [
        'status' => 'boolean',
    ];


    protected static function booted(): void
    {
        parent::booted();

        static::created(function (Branch $branch) {
            $departments = config('departments', []);

            foreach ($departments as $department) {
                $branch->departments()->create([
                    'name' => $department['name'],
                    'branch_id' => $branch->id,
                    'is_default' => $department['is_default'] ?? 0,
                    'status' => $department['status'] ?? 1,
                ]);
            }

            // add new branch to all super admin users accessible branches
            $superAdminUserIds = static::getUserModelClass()::where('role_id', 1)->pluck('id');
            $branch->usersWithAccess()->attach($superAdminUserIds);

            //for remove this later
            // $superAdminUsers = User::where('role_id', 1)->get();
            // foreach ($superAdminUsers as $user) {
            //     $user->accessibleBranches()->attach($branch->id);
            // }
        });

        static::deleted(function (Branch $branch) {
            // Remove branch from all super admin users accessible branches
            $superAdminUserIds = static::getUserModelClass()::where('role_id', 1)->pluck('id');
            $branch->usersWithAccess()->detach($superAdminUserIds);

            //for remove this later
            // $superAdminUsers = User::where('role_id', 1)->get();
            // foreach ($superAdminUsers as $user) {
            //     $user->accessibleBranches()->detach($branch->id);
            // }
        });
    }


    # START RELATIONSHIPS
    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo( $this->getBranchModelClass() , 'parent_id');
    }

    protected function getCountryModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Country::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo($this->getCountryModelClass() , 'country_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany( $this->getBranchModelClass() , 'parent_id');
    }

    protected function getDepartmentModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType( Department::class );
    }

    public function departments(): HasMany
    {
        return $this->hasMany( $this->getDepartmentModelClass() , 'branch_id');
    }

    protected static function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    public function users(): HasMany
    {
        return $this->hasMany( static::getUserModelClass() , 'branch_id');
    }

    public function usersWithAccess(): BelongsToMany
    {
        return $this->belongsToMany( static::getUserModelClass() , 'accessible_branch_user', 'branch_id', 'user_id');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany( 
                                        static::getUserModelClass() ,
                                        'standard_committee_branch_team',
                                        'branch_id',
                                        'user_id'
                                   )
                    ->withPivot('standard_committee_id');
    }
    # END RELATIONSHIPS
    
    public function getOwnedRelationships(): array
    {
        return [
            OwnedRelationshipComponent::create('departments', 'branch_id')
                                      ->setUpdatingConditionColumns(['id', 'branch_id']),

        ];
    }
# START SCOPES
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    # END SCOPES

    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    public static function getMainBranchName(): string
    {
        return "Main Branch";
    }

    public static function findMainBranch(): self
    {
        return static::where("name", static::getMainBranchName())->first();
    }

    public function isHeadquarter(): bool
    {
        return $this->name == $this::getMainBranchName();
    }

}
