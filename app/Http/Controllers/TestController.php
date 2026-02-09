<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    //
    public function test(Request $request)
    {
       $products = Product::when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
    ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
    ->when($request->search, fn($q) => $q->where(function($q2) use ($request) {
        $q2->where('name', 'like', '%' . $request->search . '%')
           ->orWhere('description', 'like', '%' . $request->search . '%');
    }))
    ->select('brand_id', DB::raw('COUNT(*) as total'))
    ->groupBy('brand_id')
    ->get();

       return response()->json(
        [
            'success' => true,
            'data' => $products,
        ]
    ,200);
    }
}
