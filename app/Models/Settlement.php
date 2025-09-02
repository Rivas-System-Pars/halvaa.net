<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    const STATUS_PENDING = "pending";
    const STATUS_REJECTED = "rejected";
    const STATUS_DONE = "done";
    const STATUS_CANCELED = "canceled";

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REJECTED,
        self::STATUS_DONE,
        self::STATUS_CANCELED,
    ];

    protected $guarded = ['id'];

    protected $casts=[
        'done_at'=>'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
