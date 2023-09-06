<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            "total" => $this->total,
            "tax" => $this->tax,
            "item_quantity" => array_sum($this->voucherRecords()->pluck("quantity")->toArray()),
            "net_total" => $this->net_total,
            "staff" => $this->user->name,
            "time" => $this->created_at->format("d m Y"),
            // "updated_at" => $this->updated_at->format("d m Y"),
        ];
    }
}
