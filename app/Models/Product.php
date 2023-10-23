<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;


    protected $fillable = ["name", "brand_id", "actual_price", "sale_price", "total_stock", "unit", "more_information", "user_id", "photo"];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get the user associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestStock(): HasOne
    {
        return $this->hasOne(Stock::class)->latestOfMany();
    }

    public function voucherRecords()
    {
        return $this->hasMany(VoucherRecord::class);
    }


}
