<?php

namespace App\Http\Requests\EmployeesRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', Rule::unique('employees', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
//            'phone_number' => ['required'],
            'profile_photo' => ['sometimes','image', 'mimes:jpg,png,jpeg,svg', 'max:2048'],
        ];
    }

    public function messages() {
        return [
            'profile_photo.image' => __("The profile photo must be an image."),
            'profile_photo.mimes' => __("The profile photo can have the following extensions:") . " jpg, png, jpeg, svg",
            'profile_photo.max' => __("La photo de profil ne doit pas dépasser") . " 2 MB",
        ];
    }
}
