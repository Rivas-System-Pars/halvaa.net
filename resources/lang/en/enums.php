<?php

use App\Models\Order;
use App\Models\ReturnedProduct;

return [
    Order::class => [
        Order::SETTLEMENT_TYPE_CASH => "Cash",
        Order::SETTLEMENT_TYPE_CHEQUE => "Cheque",
        Order::SETTLEMENT_TYPE_CASH_CHEQUE => "Cash-Cheque",
    ],
    ReturnedProduct::class => [
        ReturnedProduct::TYPE_HEALTHY => "Healthy",
        ReturnedProduct::TYPE_WASTAGE => "Wastage",
    ]
];
