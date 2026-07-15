<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'sub_product_id' => 'required|exists:sub_products,id',
            'account_id' => 'required|string|max:255',
            'payment_method' => 'required|in:balance,midtrans,ipaymu',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'The product field is required',
            'product_id.exists' => 'The selected product is invalid',
            'sub_product_id.required' => 'The product variant field is required',
            'sub_product_id.exists' => 'The selected product variant is invalid',
            'account_id.required' => 'The account ID field is required',
            'payment_method.required' => 'The payment method field is required',
            'payment_method.in' => 'The payment method must be one of: balance, midtrans, ipaymu',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Additional validation logic can be added here
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}