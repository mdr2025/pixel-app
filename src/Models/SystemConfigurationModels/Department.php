<?php

namespace PixelApp\Models\SystemConfigurationModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    HasMany,
    BelongsTo
};
 use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Models\PixelBaseModel;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\UsersModule\PixelUser;

class Department extends PixelBaseModel
{
    use HasFactory , SoftDeletes;

    protected $table = "departments";
    const ROUTE_PARAMETER_NAME = "department";

    protected $fillable = [
        'name',
        'status',
        'is_default',
        'branch_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_default' => 'boolean'
    ];
    protected $appends = [
        'managers_ids',
        'reps_ids',
    ];

    // Accessors
    // need to eager loading while handling acollection of departments 
    // or they are will be loaded separatety (n + 1 problem)
    public function getManagersIdsAttribute()
    {
        return $this->managers?->pulck('id')->toArray() ?? [];

        //to avoid executing a new query each time 
        //becase getSomePropertyAtrribute method is getting a value from attributes 
        // if($this->relationLoaded('managers'))
        // {
        //   return $this->managers?->pulck('id')->toArray() ?? [];
        // }

        // return $this->managers()->pluck('id')->toArray();
    }

    // need to eager loading while handling acollection of departments ... or they are will be loaded separatety (n + 1 problem)
    public function getRepsIdsAttribute()
    {
         return $this->reps?->pulck('id')->toArray() ?? [];

        //to avoid executing a new query each time 
        //becase getSomePropertyAtrribute method is getting a value from attributes 
        // if($this->relationLoaded('reps'))
        // {
        //     return $this->reps?->pulck('id')->toArray() ?? [];
        // }

        // return $this->reps()->pluck('id')->toArray();
    }
    
    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    # START SCOPES
    public function scopeActive(Builder $query)
    {
        $query->where('status', 1);
    }

    public function scopeHasDepartmentTeam(Builder $query, int $userId): Builder
    {
        return $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('id', $userId)
                    ->whereIn('dep_role', [
                        PixelUser::DEP_TYPE_MANAGER,
                        PixelUser::DEP_TYPE_ENGINEER,
                        PixelUser::DEP_TYPE_REP,
                    ]);
                });
    }

    //t oremove later
    // public function scopeHasDepartmentTeam(Builder $query, int $userId)
    // {
    //     $query->where(function ($subQuery) use ($userId): void {
    //         $subQuery->whereRelation('managers', 'id', $userId)
    //             ->orWhereRelation('reps', 'id', $userId);
    //     });
    // }
    # END SCOPES


    # START RELATIONSHIPS

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    public function users(): HasMany
    {
        return $this->hasMany( $this->getUserModelClass() );
    }

    public function managers(): HasMany
    {
        return $this->hasMany( $this->getUserModelClass() , 'department_id', 'id')->where('dep_role', PixelUser::DEP_TYPE_MANAGER);
    }

    public function reps(): HasMany
    {
        return $this->hasMany( $this->getUserModelClass() , 'department_id', 'id')->where('dep_role', PixelUser::DEP_TYPE_REP);
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType( Branch::class );
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo( $this->getBranchModelClass() , 'branch_id');
    }
    # END RELATIONSHIPS
}
