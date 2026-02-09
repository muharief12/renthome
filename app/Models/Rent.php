<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rent extends Model
{
    protected $table = 'rents';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function rentDetails(): HasMany
    {
        return $this->hasmany(RentDetail::class, 'rent_id');
    }

    public function rentPayments(): HasMany
    {
        return $this->hasMany(RentPayment::class, 'rent_id');
    }
}
