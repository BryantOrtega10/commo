<?php

namespace App\Http\Requests\Commissions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditCommissionRateRequest extends FormRequest
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
            "business_segment" => 'nullable',
            "business_type" => 'nullable',
            "compensation_type" => 'nullable',
            "amf_compensation_type" => 'nullable',
            "plan_type" => 'nullable',
            "product" => 'nullable',
            "product_type" => 'nullable',
            "tier" => 'nullable',
            "county" => 'nullable',
            "region" => 'nullable',
            "policy_contract_id" => 'nullable',
            "tx_type" => 'nullable',
            "submit_from" => 'nullable',
            "submit_to" => 'nullable',
            "statement_from" => 'nullable',
            "statement_to" => 'nullable',
            "original_effective_from" => 'nullable',
            "original_effective_to" => 'nullable',
            "benefit_effective_from" => 'nullable',
            "benefit_effective_to" => 'nullable',
            "flat_rate" => 'nullable',
            "rate_type" => 'nullable',
            "rate_amount" => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator, 'editRate')  
                ->withInput()
        );
    }
}
