<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'internal_code' => 'required|string|unique:resources,internal_code',
            'total_quantity' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,inactive,maintenance',
            'observations' => 'nullable|string',
        ];
    }
}
