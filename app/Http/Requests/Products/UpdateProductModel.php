<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductModel extends FormRequest
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
        $rules = [
            "description" => 'required',
            "carrier" => 'nullable',
            "business_segment" => 'nullable',
            "business_type" => 'nullable',
            "product_type" => 'nullable',
            "plan_type" => 'nullable',
            "tier" => 'nullable',
            "alias" => 'nullable|array',
            "aliasIDs" => 'nullable|array',
            "alias.*" => [Rule::unique('products','description')],
        ];
        
        foreach ($this->alias as $index => $alias) {
            $aliasID = $this->aliasIDs[$index] ?? null;
        
            $rules["alias.$index"] = [
                'nullable',
                'string',
                Rule::unique('product_alias', 'alias')->ignore($aliasID),
            ];
        }
        
        return $rules;
    }
}
