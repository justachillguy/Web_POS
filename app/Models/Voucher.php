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

    public static function getDistinctYears()
    {
        return self::selectRaw('YEAR(created_at) as year')
            ->distinct()            // Retrieve only distinct years
            ->orderBy('year', 'asc')  // Order the years in ascending order
            ->pluck('year');         // Retrieve the "year" values and return them as a collection
    }
}
