<?php

namespace App\Http\Requests\Agents;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateAgentNumbersRequest extends FormRequest
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
            'number' => 'required',
            'agency_code' => 'nullable',
            'carrier' => 'required',
            'agent_title' => 'nullable',
            'agent_status' => 'nullable',
            'agency' => 'nullable',
            'contract_rate' => 'nullable',
            'admin_fee' => 'nullable',
            'notes' => 'nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator, 'addNewAgentNumberForm')  
                ->withInput()
        );
    }
}
