<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "brand_name" => $this->brand->name,
            "price" => $this->sale_price,
            "stocks" => $this->total_stock,
            "note" => $this->more_information,
            "photo" => asset(Storage::url($this->photo)),
        ];
    }
}
