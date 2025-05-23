<?php

namespace App\Http\Requests\Leads;

use App\Models\Customers\CustomersModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLeadRequest extends FormRequest
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
        $ignoreSSN = "";
        if(request()->route('id') !== null){
            $customers = CustomersModel::find(request()->route('id'));
            $ignoreSSN = $customers->ssn;
        }

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'date_birth' => 'nullable',
            'ssn' => ['nullable', Rule::unique('customers','ssn')->ignore($ignoreSSN, 'ssn')],
            'email' => 'required',
            'phone' => ['required', 'max:10', 'not_regex:/^1/', Rule::unique('customers','phone')],
            'registration_source' => 'nullable',
            'referring_customer_id' => ['nullable', function ($attribute, $value, $fail) {
                if ($value === request()->route('id')) {
                    $fail('The :attribute cannot be the same customer');
                }
            }],
            'status' => 'nullable',
            'phase' => 'nullable',
            'legal_basis' => 'nullable',
        ];
    }

    public function messages(){
        return [
            "last_name.requiredif" => 'Last name required if business type is individual',
            "date_birth.requiredif"  => 'Date of birth required if business type is individual',
            "phone.not_regex"  => 'The phone number cannot start with 1',
        ];
    }
}
