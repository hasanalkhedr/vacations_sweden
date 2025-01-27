<?php

namespace App\Http\Requests\EmployeesRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeePasswordRequest extends FormRequest
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
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'password.min' => __("The minimum length is 6 characters."),
            'password.confirmed' => __("The password confirmation does not match."),
        ];
    }
}
