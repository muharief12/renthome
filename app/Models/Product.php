<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class, 'product_id');
    }

    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class, 'product_id');
    }
}
