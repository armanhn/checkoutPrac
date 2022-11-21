<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEnrollmentRequest extends FormRequest
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
        return
            [
                'student_name' => 'required|string',
                'student_email' => 'required',
                'school_attended' => 'nullable|string',
                'gender' => 'required|string',
                'dob' => 'required|string',
                'student_cell' => 'required|string',
                'home_phone' => 'required|string',
                'address' => 'required|string',
                'parent_name' => 'nullable|string',
                'parent_email' => 'nullable',
                'parent_cell' => 'nullable|string',
            ];
    }
}
