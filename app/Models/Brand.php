<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;

    // public function stocks()
    // {
    //     return $this->hasManyThrough(Stock::class, Product::class);
    // }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function brand()
    {
        return $this->hasManyThrough(VoucherRecord::class, Product::class);
    }


    protected $fillable = ["name", "company", "information", "user_id", "photo", "agent", "phone_number"];
}
