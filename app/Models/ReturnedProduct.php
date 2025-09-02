<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnedProduct extends Model
{

    const TYPE_HEALTHY = "healthy";
    const TYPE_WASTAGE = "wastage";
    const TYPES = [
        self::TYPE_HEALTHY,
        self::TYPE_WASTAGE,
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'type',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
