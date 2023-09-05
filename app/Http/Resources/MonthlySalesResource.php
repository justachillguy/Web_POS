<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySalesResource extends JsonResource
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
            "date" => $this->created_at->format("d F Y"),
            "vouchers" => $this->vouchers,
            "cash" => $this->total,
            "tax" => $this->tax,
            "total" => $this->net_total,
        ];
    }
}
