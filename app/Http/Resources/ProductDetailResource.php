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
            "id" => $this->id,
            "name" => $this->name,
            "brand_name" => $this->brand->name,
            "actual_price" => $this->actual_price,
            "sale_price" => $this->sale_price,
            "stocks" => $this->total_stock,
            "unit" => $this->unit,
            "more_information" => $this->more_information,
<<<<<<< HEAD
=======
            "photo" => $this->photo,
>>>>>>> a2a76d045a9d518544805d5e81cccf40cdce2675
            "stock_history" => $this->stocks,
        ];
    }
}
