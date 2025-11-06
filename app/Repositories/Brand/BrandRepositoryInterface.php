<?php 

namespace App\Repositories\Brand;

use Illuminate\Http\Request;

interface BrandRepositoryInterface
{
    public function getBrands(Request $request);

    public function getBrandById($id);

    public function createBrand(array $data);

    public function updateBrand($id, array $data);

    public function deleteBrand($id);
}