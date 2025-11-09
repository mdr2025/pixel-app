<?php

namespace PixelApp\Http\Requests\SystemConfigurationRequests\Branches;

use PixelApp\Models\SystemConfigurationModels\Branch;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use ValidatorLib\CustomFormRequest\BaseFormRequest;

class BranchAddTeamRequest extends BaseFormRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return BasePolicy::check('edit', Branch::class);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'department_id' => 'required|exists:departments,id',

            'managers_ids' => 'nullable|array',
            'managers_ids.*' => 'integer|exists:users,id',

            'reps_ids' => 'nullable|array',
            'reps_ids.*' => 'integer|exists:users,id',
        ];
    }
}
