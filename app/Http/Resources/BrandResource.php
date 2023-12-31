<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            "brand_name" => $this->name,
            "company" => $this->company,
            "agent" => $this->agent,
            "phone_number" => $this->phone_number,
            "photo" => $this->photo,
        ];
    }
}
