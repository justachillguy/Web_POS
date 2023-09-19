<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->total_stock > 30) $stock_level = "In Stock";
        if($this->total_stock <= 30 && $this->total_stock >= 1) $stock_level = "Low Stock";
        if($this->total_stock === 0 ) $stock_level = "Out Of Stock";

        return [
            "id" => $this->id,
            "name" => $this->name,
            "brand_name" => $this->brand->name,
            "unit" => $this->unit,
            "sale_price" => $this->sale_price,
            "total_stock" => $this->total_stock,
            "stock_levle" => $stock_level,
        ];
    }
}
