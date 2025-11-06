<?php 

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProducts(Request $request)
    {
       $products = Product::orderBy('created_at', 'desc')->paginate(config('app.per_page'));
       return response()->json(
           [
               'success' => true,
               'data' => $products,
           ],
           200
       );
    }

    public function getProductById($id)
    {
        $product = Product::with(['category', 'brand', 'images'])->find($id);
        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }

    public function createProduct(array $data)
    {
        // Implementation code here
    }

    public function updateProduct(array $data, $id)
    {
        // Implementation code here
    }

    public function deleteProduct($id)
    {
        // Implementation code here
    }
}