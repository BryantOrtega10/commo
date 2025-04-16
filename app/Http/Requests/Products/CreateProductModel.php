<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductModel extends FormRequest
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
            "description" => 'required',
            "carrier" => 'nullable',
            "business_segment" => 'nullable',
            "business_type" => 'nullable',
            "product_type" => 'nullable',
            "plan_type" => 'nullable',
            "tier" => 'nullable',
            "alias" => 'nullable|array',
            "alias.*" => ['nullable', Rule::unique('product_alias','alias'), Rule::unique('products','description')],
        ];
    }
}
