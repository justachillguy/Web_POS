<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "product_id",
        "quantity",
        "more"
    ];

    public function productBrand()
    {
        return $this->belongsTo(Brand::class, Product::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
