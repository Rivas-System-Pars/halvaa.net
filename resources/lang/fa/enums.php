<?php

use App\Models\Order;
use App\Models\ReturnedProduct;

return [
    Order::class => [
        Order::SETTLEMENT_TYPE_CASH => "نقدی",
    ],
    ReturnedProduct::class => [
        ReturnedProduct::TYPE_HEALTHY => "سالم",
        ReturnedProduct::TYPE_WASTAGE => "ضایعات",
    ]
];
