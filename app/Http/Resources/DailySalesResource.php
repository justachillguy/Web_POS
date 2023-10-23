<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailySalesResource extends JsonResource
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
            "voucher_number" => $this->voucher_number,
            "time" => $this->created_at->format("h:i"),
            "item_count" => array_sum($this->voucherRecords()->pluck("quantity")->toArray()),
            "cash" => $this->total,
            "tax" => $this->tax,
            "total" => $this->net_total,
        ];
    }
}
