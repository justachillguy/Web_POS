<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlySale extends Model
{
    use HasFactory;

    protected $fillable = [
        "vouchers",
        "total",
        "tax",
        "net_total",
    ];
}