<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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



    public function updateProduct(Request $request, $id)
    {
        $data = $request->all();
        return $this->productRepo->updateProduct($data, $id);
    }

    public function deleteProduct($id)
    {
        return $this->productRepo->deleteProduct($id);
    }
}
