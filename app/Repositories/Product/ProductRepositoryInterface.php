<?php

namespace App\Repositories\Product;

use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function getProducts(Request $request);

    public function getProductById($id);

    public function createProduct(array $data);

    public function createProductVariant(array $data, int $productId);
    
    public function getVariantById($id);

    public function deleteVariant($id);

    public function deleteProductImage($id);

    public function updateProduct(array $data, $id);

    public function deleteProduct($id);

    public function customerFavProduct(int $productId);

    public function customerSaveProduct(int $productId);

    public function productVariantById(int $productVariantId);
}
