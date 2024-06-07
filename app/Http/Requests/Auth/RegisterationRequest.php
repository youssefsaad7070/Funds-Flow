<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterationRequest extends FormRequest
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
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email', 'gmail_domain'],
            'role' => 'required|in:investor,business',
            'gender' => 'sometimes|in:Male,Female',
            'national_id' => 'sometimes|unique:investors,national_id',
            'tax_card_number' => 'sometimes|unique:businesses,tax_card_number',
            'password' => 'required|min:8',
            'description' => 'sometimes',
            'id_card_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()
        ], 422));
    }
}
