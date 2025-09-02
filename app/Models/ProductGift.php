<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductGift extends Model
{
    protected $fillable=[
        'product_id',
        'quantity',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_gift_product')
            ->withPivot(['quantity']);
    }
}
