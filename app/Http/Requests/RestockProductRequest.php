<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StockHistory;

class RestockProductRequest extends FormRequest
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
            // 'adjustment_type' => 'required|in:' . implode(',', array_keys(StockHistory::getAdjustmentTypes())),
            'quantity_change' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
