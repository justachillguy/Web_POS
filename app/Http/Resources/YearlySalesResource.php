<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YearlySalesResource extends JsonResource
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
            "month" => $this->created_at->format("F"),
            "year" => $this->created_at->format("Y"),
            "vouchers" => $this->vouchers,
            "cash" => $this->total,
            "tax" => $this->tax,
            "total" => $this->net_total,
        ];
    }
}
