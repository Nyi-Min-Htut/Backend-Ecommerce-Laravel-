<?php

namespace App\Repositories\Order;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function getOrders(Request $request);

    public function createOrder(Request $request);
}