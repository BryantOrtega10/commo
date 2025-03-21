<?php

namespace App\Http\Requests\customers;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_type' => 'required',
            'first_name' => 'required',
            'middle_initial' => 'nullable',
            'last_name' => 'requiredif:business_type,1',
            'suffix' => 'nullable',
            'date_birth' => 'requiredif:business_type,1',
            'ssn' => 'nullable',
            'gender' => 'nullable',
            'matiral_status' => 'nullable',
            'address' => 'nullable',
            'address_2' => 'nullable',
            'email' => 'nullable',
            'county' => 'nullable',
            'city' => 'nullable',
            'zip_code' => 'nullable',
            'phone' => 'nullable',
            'phone_2' => 'nullable',
            'registration_source' => 'nullable',
            'referring_customer_id' => 'nullable',
            'contact_agent_id' => 'nullable',
            'status' => 'nullable',
            'phase' => 'nullable',
            'legal_basis' => 'nullable',
        ];
    }
}
