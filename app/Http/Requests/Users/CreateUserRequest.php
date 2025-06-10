<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
        $entry_user = Auth::user();
        $available_roles = [];
        if ($entry_user->role == "admin") {
            $available_roles = ["supervisor", "admin"];
        }
        if ($entry_user->role == "superadmin") {
            $available_roles = ["supervisor", "admin", "superadmin"];
        }

        return [
            'name' => 'required',
            'email' => ['required',Rule::unique('users','email')],
            'role' => [
                'required',
                Rule::in($available_roles),
            ],
            'status' => 'required',
            'password' => 'required',
            'repeat_password' => 'same:password',
            'image' => 'nullable|image'
        ];
    }
}
