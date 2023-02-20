<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // $this user its related to user param in user controller
        return [
            'name' => 'required|string|max:55',
            'email' => [  Rule::unique('users')->ignore($this->user)],
            // 'email' => 'required|email|unique:users,email,'.$this->user,
            'mobile' => 'required',
            'role' => 'required',
            // 'image' => 'mimes:jpg,png,jpeg'
        ];
    }
}
