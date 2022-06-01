<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'name' => ['required','min:5'],
            'address' => ['required','min:5'],
            'join_date' => ['required','date'],
            'department_id' => ['required','int'],
            'manager_id' => ['sometimes','required','int'],
            'role_id' => ['required','int'],
        ];
    }

    public function prepareForValidation() {

        if($this->role_id == config('role.admin')) {
            $this->request->remove('manager_id');
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 5 characters',
            'address.required' => 'Address is required',
            'address.min' => 'Address must be at least 5 characters',
            'join_date.required' => 'Join Date is required',
            'join_date.date' => 'Join Date must be a valid date',
            'department_id.required' => 'Department is required',
            'department_id.int' => 'Department must be an integer',
            'manager_id.required' => 'Manager is required',
            'manager_id.int' => 'Manager must be an integer',
            'role_id.required' => 'Role is required',
            'role_id.int' => 'Role must be an integer',
        ];
    }
}
