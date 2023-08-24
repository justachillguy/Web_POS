<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemsInVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "product_name" => $this->product->name,
            "price" => $this->product->sale_price,
            "quantity" => $this->quantity,
            "cost" => $this->cost,
        ];
    }
}
