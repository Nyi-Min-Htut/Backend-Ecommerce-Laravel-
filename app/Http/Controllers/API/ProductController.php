<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;

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
}
