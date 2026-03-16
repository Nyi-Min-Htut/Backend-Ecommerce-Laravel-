<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
public function getProducts(Request $request)
{
    $query = Product::with(['category', 'brand', 'productImages', 'productVariants'])
        ->orderBy('created_at', 'desc');

    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Paginate the results
    $products = $query->paginate(config('app.per_page'));

    // Add total_stock to each product using sum() method
    $products->getCollection()->each(function ($product) {
        $product->total_stock = $product->productVariants->sum('stock');
    });

    return response()->json([
        'success' => true,
        'data' => $products,
    ], 200);
}


    public function getProductById($id)
    {
        $product = Product::with(['category', 'brand', 'productImages', 'productVariants.productImages', 'productVariants.attributes'])->find($id);
        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }

    public function createProduct(array $data)
    {
        DB::beginTransaction();
        try {
            $product = Product::create($data);
            if ($data['product_main_img']) {
                $path = $data['product_main_img']->store('products', 'public');
                $url = asset('storage/' . $path);
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $url,
                'image_path' => $path,
                'product_variant' => null,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createProductVariant(array $data, int $productId)
    {
        DB::beginTransaction();

        try {

            $variant = ProductVariant::create([
                'product_id' => $productId,
                'name' => $data['name'],
                'price' => $data['price'] ?? null,
                'stock' => $data['stock'],
                'description' => $data['description'] ?? null,
            ]);

            $product = Product::find($productId);
            $product->save();
            $attributes = isset($data['attributes']) ? json_decode($data['attributes'], true) : [];

            if (!empty($attributes)) {

                foreach ($attributes as $attrId => $value) {
                    $variant->attributes()->attach($attrId, ['value' => $value]);
                }
            }


            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $image->store('products', 'public');
                    $url = asset('storage/' . $path);

                    ProductImage::create([
                        'product_id' => $productId,
                        'image_url' => $url,
                        'image_path' => $path,
                        'product_variant_id' => $variant->id,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $variant
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getVariantById($id)
    {
        $productVariant = ProductVariant::with(['attributes', 'productImages'])->find($id);
        return response()->json([
            'success' => true,
            'data' => $productVariant,
        ], 200);
    }

    public function deleteVariant($id)
    {
        $productVariant = ProductVariant::find($id);
        $productVariant->productImages()->delete();
        $productVariant->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product variant deleted successfully.',
        ], 200);
    }

    public function deleteProductImage($id)
    {
        $productImage = ProductImage::find($id);
        $productImage->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product image deleted successfully.',
        ], 200);
    }


    public function updateProduct(array $data, $id)
    {
        DB::beginTransaction();
        try {
            if($product = Product::find($id)){
                $product->update($data);
            }
            if ($data['product_main_img']) {
                $path = $data['product_main_img']->store('products', 'public');
                $url = asset('storage/' . $path);
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $url,
                'image_path' => $path,
                'product_variant' => null,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteProduct($id)
    {
        // Implementation code here
    }

    public function customerFavProduct(int $productId)
    {
        $userId = auth()->guard('customer')->user()->id;
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }else{
            $isFav = $product->favCustomer()->where('customer_id', $userId)->exists();

            if ($isFav) {
                $product->favCustomer()->detach($userId);
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from favorites.',
                ], 200);
            } else {
                $product->favCustomer()->attach($userId);
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to favorites.',
                ], 200);
            }
        }
    }

    public function customerSaveProduct(int $productId)
    {
        $userId = auth()->guard('customer')->user()->id;
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }else{
            $isSaved = $product->saveCustomer()->where('customer_id', $userId)->exists();

            if ($isSaved) {
                $product->saveCustomer()->detach($userId);
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from saved products.',
                ], 200);
            } else {
                $product->saveCustomer()->attach($userId);
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to saved products.',
                ], 200);
            }
        }
    }

    // public function customerRatingProduct(array $data)
    // {
    //     $userId = auth()->guard('customer')->user()->id;
    //     $product = Product::find($data['product_id']);
    //     if(!$product)
    //         {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => "Product not found",
    //             ],404);
    //         }else{
    //             $isRating = $product->ratingCustomer()->where('customer_id',$userId)->exists();
    //             if($isRating)
    //                 {
    //                     return response()->json([
    //                         'success' => true,
    //                         'message' => 'Product that you rated is already rated by you',
    //                     ],200);
    //                 }else{
    //                     $product->ratingCustomer()->attach($data)
    //                 }
    //         }
    // }

    public function productVariantById(int $productVariantId)
    {
        $productVariant = ProductVariant::where('id',$productVariantId)->with(['product','productImages','attributes'])->get();
        return response()->json([
            'success'=> true,
            'data'=>$productVariant
        ]);
    }
}
