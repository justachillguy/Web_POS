<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "min:3", "max:30", "string"],
            "brand_id" => ["required", "exists:brands,id"],
            "actual_price" => ["required", "integer"],
            // "total_stock" => ["required", "integer"],
            "sale_price" => ["required", "integer"],
            "unit" => ["required", "string"],
            "more_information" => ["nullable","string", "max:225"],
            "user_id" => "exists:users,id",
        ];
    }
}
