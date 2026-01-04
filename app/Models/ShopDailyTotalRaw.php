<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopDailyTotalRaw extends Model
{
    protected $table = 'shop_daily_total_raw';
    public $timestamps = false;

    public function dailyTotal(): BelongsTo
    {
        return $this->belongsTo(ShopDailyTotal::class);
    }
}