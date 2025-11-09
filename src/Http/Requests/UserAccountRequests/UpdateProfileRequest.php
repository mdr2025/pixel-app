<?php

namespace PixelApp\Http\Requests\UserAccountRequests;
 
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsModelKeyAdvancedValidation;
use CRUDServices\Interfaces\ValidationManagerInterfaces\NeedsRelationshipsKeyAdvancedValidation;
use Illuminate\Validation\Rule;
use PixelApp\Models\UsersModule\UserProfile;
use PixelApp\Rules\PhoneNumber;
use ValidatorLib\CustomFormRequest\BaseFormRequest;
use ValidatorLib\CustomValidationRules\FileValidationRules\SingleFileOrSinglePathString;

class UpdateProfileRequest extends BaseFormRequest implements NeedsModelKeyAdvancedValidation , NeedsRelationshipsKeyAdvancedValidation
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    { 
        return BasePolicy::check('edit', UserProfile::class);
    }

    public function getRelationshipKeyAdvancedValidationRules(string $relationshipName, array $data = []): array
    {
        return match($relationshipName){

            'profile'    => $this->getProfileAdvancedValidationRules($data),
            default => []

        } ;
    }
    public function getModelKeyAdvancedValidationRules(array $data = []): array
    {
        $id = $data["id"];
        return [
                    "email" => [ Rule::unique("users" , "email")->ignore($id)],
                    "mobile" => [ Rule::unique("users","mobile")->ignore($id )],
               ];
    }
    public function getProfileAdvancedValidationRules(array $data = []): array
    {
        $user_id = $data["user_id"];
        return   [

                        "national_id_number" => ["nullable" ,Rule::unique("user_profile", "national_id_number")->ignore($user_id, "user_id")],
                        "passport_number" => ["nullable" , Rule::unique("user_profile", "passport_number")->ignore($user_id, "user_id")],
                        "nationality_id" => ["nullable" , "exists:countries,id"]
                 ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email" => ["required" , "string" , "email"],
            "full_name" => ["nullable" , "string"],
            "mobile" => ["required" ,  PhoneNumber::create()],
            "profile.gender" => ["nullable" , "string"  ],
            "profile.nationality_id" => ["nullable" , "string" ], 
            "profile.national_id_number" => ["nullable" , "string" ],
            "profile.passport_number" => ["nullable" , "string" ],
            "profile.marital_status" => ["nullable" , "string" , Rule::in( UserProfile::MARTIAL_STATUSES )],
            "profile.military_status" => ["nullable" , "string", Rule::in( UserProfile::MILITARY_STATUSES )],
            "profile.picture" => ["nullable" , (new SingleFileOrSinglePathString())->allowImageFilesOnly() ],
            'attachments' => ["nullable" , "array"],
            'attachments.*.path' => ["required" , new SingleFileOrSinglePathString()],
            'attachments.*.type' => ["required" , "string"],
            'attachments.*.path_original' => ["exclude" ],
        ];
    }
}
