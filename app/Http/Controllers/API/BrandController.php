<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Brand\BrandRepositoryInterface;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    //
    protected $brandRepo;

    public function __construct(BrandRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    public function getBrands(Request $request)
    {
        $brands = $this->brandRepo->getBrands($request);
        return $brands;
    }

    public function getBrandById($id)
    {
        $brand = $this->brandRepo->getBrandById($id);
        return $brand;
    }

    public function createBrand(Request $request)
    {
        $data = $request->all();
        $brand = $this->brandRepo->createBrand($data);
        return $brand;
    }

    public function updateBrand(Request $request, $id)
    {
        $data = $request->all();
        $brand = $this->brandRepo->updateBrand($id, $data);
        return $brand;
    }

    public function deleteBrand($id)
    {
        $brand = $this->brandRepo->deleteBrand($id);
        return $brand;
    }
}
