<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopDailyTotal extends Model
{
    protected $table = 'shop_daily_total';

    public function rawValues(): HasMany
    {
        return $this->hasMany(ShopDailyTotalRaw::class);
    }
}