<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInstallmentItem extends Model
{
    const STATUS_PAID = "paid";
    const STATUS_UNPAID = "unpaid";

    const STATUES = [
        self::STATUS_PAID,
        self::STATUS_UNPAID,
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function getTotalAmount()
    {
        return ceil($this->amount + $this->fee);
    }
	
	public function orderInstallment()
    {
        return $this->belongsTo(OrderInstallment::class);
    }

}
