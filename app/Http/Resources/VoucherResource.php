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
            "staff" => $this->user->name,
            "voucher_number" => $this->voucher_number,
            "item_quantity" => array_sum($this->voucherRecords()->pluck("quantity")->toArray()),
            "net_total" => $this->net_total,
            "total" => $this->total,
            "tax" => $this->tax,
            "time" => $this->created_at->format("d-m-Y H:i:s"),
            // "updated_at" => $this->updated_at->format("d m Y"),
        ];
    }
}
