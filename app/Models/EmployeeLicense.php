<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLicense extends Model
{
    public $timestamps = false;

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}