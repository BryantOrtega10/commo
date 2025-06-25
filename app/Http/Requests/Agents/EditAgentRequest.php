<?php

namespace App\Http\Requests\Agents;

use App\Models\Agents\AgentsModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditAgentRequest extends FormRequest
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
        $ignoreEmail = "";
        if(request()->route('id') !== null){
            $agent = AgentsModel::find(request()->route('id'));
            $ignoreSSN = $agent->ssn;
            $ignoreEmail = $agent->email;
        }

        return [
            "first_name" => "required",
            "last_name" => "required",
            "date_birth" => "nullable",
            "ssn" => ["required", Rule::unique("agents","ssn")->ignore($ignoreSSN,"ssn")],
            "gender" => "nullable",
            "email" => ["required", Rule::unique("users","email")->ignore($ignoreEmail,"email")],
            "phone" => "nullable",
            "phone_2" => "nullable",
            "address" => "nullable",
            "address_2" => "nullable",
            "state" => "nullable",
            "city" => "nullable",
            "zip_code" => "nullable",
            "national_producer" => "nullable",
            "license_number" => "nullable",
            "sales_region" => "nullable",
            "has_CMS" => "nullable",
            "CMS_date" => "nullable",
            "has_marketplace_cert" => "nullable",
            "marketplace_cert_date" => "nullable",
            "contract_date" => "nullable",
            "payroll_emp_ID" => "nullable",
            "contract_type" => "nullable",
            "company_name" => "nullable",
            "company_EIN" => "nullable",
            "agent_notes" => "nullable",
        ];
    }
}
