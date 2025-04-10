<?php

namespace App\Http\Requests\Leads;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateActivityRequest extends FormRequest
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
            'type' => 'required',
            'html_desc' => 'required',
            'description' => 'required',
            'create_task' => 'nullable',
            'task_name' => 'required_with:create_task',
            'expiration_date' => 'required_with:create_task',
            'expiration_hour' => 'required_with:create_task',
            'priority' => 'required_with:create_task',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator, 'addActivityForm')
                ->withInput()
        );
    }
}
