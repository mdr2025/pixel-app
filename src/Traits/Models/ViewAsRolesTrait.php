<?php

namespace PixelApp\Traits\Models;

use Illuminate\Support\Facades\Auth;

/**
 * @todo need to work on optional relations to be general in pixel-app package 
 */
trait ViewAsRolesTrait
{ 
    /**
     * @return bool
     */
    public function isCreator(): bool
    {
        return (int) $this->creator_id === (int) Auth::id();
    }
  
    /**
     * @return bool
     */
    public function isOwnerRep(): bool
    {
        $user = Auth::user();
        $departmentIds = [];
        //
        if (! $this->relationLoaded('owners'))
        {
            $this->load('owners');
        }
        $departmentIds = $this->owners?->pluck('id')->toArray();
        //
        return in_array($user->department_id, $departmentIds);
    }


    /**
     * @return bool
     */
    public function isResponsibleDepartmentRep(): bool
    {
        $user = Auth::user();

        if (! $this->relationLoaded('actions.departments')) {
            $this->load('actions.departments');
        }
        $departmentIds = $this->actions
            ->flatMap(function ($action) {
                return $action->departments->pluck('id');
            })
            ->unique()
            ->toArray();

        return in_array($user?->department_id, $departmentIds);
    }
}
