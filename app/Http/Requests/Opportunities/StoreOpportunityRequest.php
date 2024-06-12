<?php

namespace App\Http\Requests\Opportunities;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOpportunityRequest extends FormRequest
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
            'business_name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'amount_needed' => 'required|numeric|min:0',
            'potential_risks' => 'required|string|max:255',
            'future_growth' => 'required|string|max:255',
            'products_or_services' => 'required|string|max:255',
            'returns_percentage' => 'required|string|min:0|max:100',
            'company_valuation' => 'required|string|min:0|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'revenues' => 'required|string|min:0',
            'net_profit' => 'required|string|min:0',
            'profit_margin' => 'required|string|min:0|max:100',
            'cash_flow' => 'required|string|min:0|max:255',
            'ROI' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
