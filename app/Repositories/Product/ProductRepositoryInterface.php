<?php 

namespace App\Repositories\Product;

use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function getProducts(Request $request);

    public function getProductById($id);

    public function createProduct(array $data);
    
    public function createProductVariant(array $data, int $productId);

    public function updateProduct(array $data, $id);

    public function deleteProduct($id);
}