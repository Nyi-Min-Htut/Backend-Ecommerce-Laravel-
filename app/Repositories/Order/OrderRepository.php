<?php 

namespace App\Repositories\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
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

     public function createOrder(Request $request)
    {
        DB::beginTransaction();
        
        try {

            if ($request->has('customer')) {
                $customerId = $request->customer->id;
                return response()->json([
                    'success' => false,
                    'message' => $customerId
                ], 404);
            }
            // If guest checkout, check if customer exists by phone/email
            else {
                $customer = Customer::where('phone_number', $request->customer_phone)
                        ->first();

                  
                $customerId = $customer->id ;
                
            }

       
            
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