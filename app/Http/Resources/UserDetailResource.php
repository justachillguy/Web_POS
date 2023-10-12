<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserDetailResource extends JsonResource
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
            "phone_number" => $this->phone_number,
            "date_of_birth" => $this->date_of_birth,
            "gender" => $this->gender,
            "address" => $this->address,
            "position" => $this->position,
            "email" => $this->email,
            // "photo" => asset(Storage::url($this->photo)),
            "photo" => $this->photo,
        ];
    }
}
