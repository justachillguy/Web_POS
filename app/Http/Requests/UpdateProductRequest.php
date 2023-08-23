<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            "name" => ["nullable", "min:3", "max:30", "string"],
            "brand_id" => ["nullable", "exists:brands,id"],
            "actual_price" => ["nullable", "integer"],
            "sale_price" => ["nullable", "integer"],
            "unit" => ["nullable", "string"],
            "more_information" => ["nullable", "string", "max:225"],
            "user_id" => ["nullable", "exists:users,id"],
            "photo" => ['nullable','string']
        ];
    }
}
