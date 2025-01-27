<?php

namespace App\Http\Requests\LeaveRequests;

use App\Models\LeaveType;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
        if(LeaveType::where('id', $this->leave_type_id)->first()->name == "sick leave")
        {
            $rules['attachment_path'] = ['required'];
        }
        $rules['leave_duration_id'] = ['required'];
        $rules['from'] = ['required'];
        $rules['to'] = ['required'];
        $rules['travelling'] = ['required'];
        $rules['leave_type_id'] = ['required'];

        return $rules;
    }

    public function messages()
    {
        return [
            'attachment_path.required' => __("The attachment is required"),
            'leave_duration_id.required' =>  __("The leave duration type is required"),
            'from.required' =>  __("The date is required"),
            'to.required' =>  __("The date is required"),
            'travelling.required' =>  __("The leave type is required"),
            'leave_type_id.required' =>  __(""),
        ];
    }
}
