<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentDetail extends Model
{
    protected $table = 'rent_details';
    protected $guarded = ['id'];

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'id');
    }
}
