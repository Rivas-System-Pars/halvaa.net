<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Basket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return BelongsToMany
     */
    public function gifts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'basket_product_gift')
            ->withPivot(['quantity']);
    }
}
