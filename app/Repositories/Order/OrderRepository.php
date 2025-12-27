<?php

namespace App\Repositories\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function getOrders(Request $request)
    {
        $orders = Order::with('customer')->latest()->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $orders,
            ],
            200
        );
    }

    public function getOrderByUserID()
    {
        $userId = auth()->guard('customer')->user()->id;
        $orders = Order::where('customer_id', $userId)
            ->select('id', 'order_code', 'status', 'total_amount')
            ->with([
                'orderItems:id,order_id,product_variant_id',
                'orderItems.productVariant:id,name',
                'orderItems.productVariant.productImages:id,product_variant_id,image_url'
            ])
            ->paginate(config('app.per_page'))->through(function ($oder) {
                return [
                    'id' => $oder->id,
                    'order_code' => $oder->order_code,
                    'status' => $oder->status,
                    'total_amount' => $oder->total_amount,
                    'items' => $oder->orderItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_variant_id' => $item->product_variant_id,
                            'product_variant_name' => $item->productVariant->name,
                            'product_image' => $item->productVariant->productImages->first() ? $item->productVariant->productImages->first()->image_url : null,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders,
        ], 200);
    }

    public function createOrder(Request $request)
    {
        DB::beginTransaction();

        try {

             $customerId = auth()->guard('customer')->user()->id;

            // Create order
            $order = Order::create([
                'customer_id' => $customerId,
                'order_code' => $request->order_code,
                'total_amount' => $request->total_amount,
                'tax' => $request->tax,
                'payment_type' => $request->payment_type,
                'status' => 'pending',
                'invoice_date' => now()->format('Y-m-d'),
            ]);

            // Create order items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_amount' => $item['quantity'] * $item['price'],
                    'status' => 'pending',
                ]);

                $productVariant = ProductVariant::find($item['product_variant_id']);
                $productVariant->stock -= $item['quantity'];
                $productVariant->save();

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => $order->load('orderItems')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Order failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
