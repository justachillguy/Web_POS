<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    use HasFactory;

    protected $fillable = [
        "vouchers",
        "total_actual_price",
        "total",
        "tax",
        "net_total"
    ];
}
