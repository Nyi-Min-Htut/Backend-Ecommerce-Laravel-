<?php 

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProducts(Request $request)
    {
       $products = Product::with('category','brand','productImages')->orderBy('created_at', 'desc')->paginate(config('app.per_page'));
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
        DB::beginTransaction();
        try{
            $product = Product::create($data);
            if($data['product_main_img'])
            {
                $path = $data['product_main_img']->store('products','public');
                $url = asset('storage/'.$path);
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $url,
                'image_path' => $path,
                'product_variant' => null,
            ]);

            DB::commit();
            return response()->json([
                'success'=>true,
                'data' => $product
            ],200);
        }catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
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