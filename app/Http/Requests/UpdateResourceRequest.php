<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
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
            'internal_code' => 'required|string|unique:resources,internal_code,' . $this->resource->id,
            'total_quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,maintenance',
            // ... los demás campos igual que el anterior
        ];
    }
}
