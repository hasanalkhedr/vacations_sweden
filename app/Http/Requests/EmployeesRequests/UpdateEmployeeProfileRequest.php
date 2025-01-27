<?php

namespace App\Http\Requests\EmployeesRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->request->has('can_submit_requests'))
        {
            $rules['nb_of_days'] = ['required'];
            $rules['overtime_minutes'] = ['required'];
        }
        $rules['first_name'] = ['required'];
        $rules['last_name'] = ['required'];
        $rules['email'] = ['required', 'email', Rule::unique('employees', 'email')->ignore($this->employee)];
//        $rules['phone_number'] = ['sometimes', Rule::unique('employees', 'phone_number')->ignore($this->employee)];
        $rules['profile_photo'] = ['sometimes','image', 'mimes:jpg,png,jpeg,svg', 'max:2048'];

        return $rules;
    }

    public function messages() {
        return [
            'profile_photo.image' => __("The profile photo must be an image."),
            'profile_photo.mimes' => __("The profile photo can have the following extensions:") . " jpg, png, jpeg, svg",
            'profile_photo.max' => __("La photo de profil ne doit pas dépasser") . " 2 MB",
        ];
    }
}
