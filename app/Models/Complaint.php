<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $guarded = ['id'];

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'id');
    }
}
