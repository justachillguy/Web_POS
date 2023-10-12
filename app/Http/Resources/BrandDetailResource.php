<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BrandDetailResource extends JsonResource
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
            "contact" => $this->phone_number,
            // "photo" => asset(Storage::url($this->photo)),
            "photo" => $this->photo,
            "created_at" => $this->created_at->format("d-m-Y"),
            "updated_at" => $this->updated_at->format("d-m-Y"),
        ];
    }
}
