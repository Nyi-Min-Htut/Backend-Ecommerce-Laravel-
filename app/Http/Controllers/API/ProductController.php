<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    protected $productRepo;
    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getProducts(Request $request)
    {
        return $this->productRepo->getProducts($request);
    }

    public function getProductById(int $id)
    {
        return $this->productRepo->getProductById($id);
    }

    public function createProduct(Request $request)
    {
        $data = $request->all();
        return $this->productRepo->createProduct($data);
    }

    public function createProductVariants(Request $request, int $productId)
    {
        $data = $request->all();
        return $variants = $this->productRepo->createProductVariant($data, $productId);
    }

    public function getVariantById($id)
    {
        return $this->productRepo->getVariantById($id);
    }

    public function deleteVariant($id)
    {
        return $this->productRepo->deleteVariant($id);
    }

    public function deleteProductImage($id)
    {
        return $this->productRepo->deleteProductImage($id);
    }

    public function updateProduct(Request $request, $id)
    {
        $data = $request->all();
        return $this->productRepo->updateProduct($data, $id);
    }

    public function deleteProduct($id)
    {
        return $this->productRepo->deleteProduct($id);
    }


    public function test(Request $request)
    {
        $brand = Brand::all();
        $products = Product::when($request->name,function($q,$search){
            $q->where('name','like','%'.$search.'%');
        })->get();
return view('home', ['products' => $products,
'brands'=>$brand]);

    }

    public function createBrand(Request $request)
    {
        $brand = Brand::create($request->all());
        return redirect()->route('home');
    }
}
