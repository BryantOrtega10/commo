<?php

namespace App\Http\Requests\Policies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePolicyRequest extends FormRequest
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
            "subscriber_id" => "required_without:first_name,last_name,date_birth,ssn",
            "first_name" => "nullable",
            "last_name" => "nullable",
            "date_birth" => "nullable",
            "ssn" => ['nullable', Rule::unique('customers','ssn')],
            "app_submit_date" => "nullable",
            "request_effective_date" => "nullable",
            "original_effective_date" => "nullable",
            "application_id" => "nullable",
            "eligibility_id" => "nullable",
            "proposal_id" => "nullable",
            "contract_id" => "required",
            "num_adults" => "nullable",
            "num_dependents" => "nullable",
            "premium" => "nullable",
            "cancel_date" => "nullable",
            "benefit_effective_date" => "nullable",
            "reenrollment" => "nullable",
            "review_note" => "nullable",
            "non_commissionable" => "nullable",
            "policy_status" => "nullable",
            "agent_number" => "required",
            "product" => "required",
            "enrollment_method" => "nullable",
            "county" => "nullable",
            "dependent_first_name.*" => "nullable",
            "dependent_last_name.*" => "nullable",
            "dependent_date_birth.*" => "nullable",
            "dependent_relationship.*" => "nullable",
            "dependent_date_add.*" => "nullable",
        ];
    }
}
