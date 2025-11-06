<?php

namespace PixelApp\Traits\Models;

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
        return (int) $this->creator_id === (int) auth()->id();
    }
  
    /**
     * @return bool
     */
    public function isOwnerRep(): bool
    {
        $user = auth()->user();
        $departmentIds = [];
        //
        if (! $this->relationLoaded('owners')) {
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
        $user = auth()->user();

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
