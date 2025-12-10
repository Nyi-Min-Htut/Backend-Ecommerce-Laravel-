<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    private $orderRepository;
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrders(Request $request)
    {
        return $this->orderRepository->getOrders($request);
    }

    public function createOrder(Request $request)
    {
        return $this->orderRepository->createOrder($request);
    }
}
