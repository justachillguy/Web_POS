<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $prodName = $this->product->name;
        $brandName = $this->product->brand->name;
        return [
            "id" => $this->id,
            "product_name" => $prodName,
            "brand_name" => $brandName,
            "quantity" => $this->quantity,
        ];
    }
}
