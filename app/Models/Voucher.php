<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ['voucher_number','total','tax','net_total','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucherRecords()
    {
        return $this->hasMany(VoucherRecord::class);
    }

}
