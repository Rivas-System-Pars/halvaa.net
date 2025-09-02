<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderInstallment extends Model
{
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(OrderInstallmentItem::class);
    }
	
	public function order()
    {
        return $this->belongsTo(Order::class);
    }
	
    public function firstUnpaidItem(): HasOne
    {
        return $this->hasOne(OrderInstallmentItem::class)->where('status', OrderInstallmentItem::STATUS_UNPAID);
    }
}
