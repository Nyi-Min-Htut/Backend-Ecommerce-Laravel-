<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandRepository implements BrandRepositoryInterface
{
    public function getBrands(Request $request)
    {
        $brands = Brand::orderBy('created_at', 'desc')->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $brands,
            ],
            200
        );
    }

    public function getBrandById($id)
    {
        $brand = Brand::find($id);
        return response()->json([
            'success' => true,
            'data' => $brand,
        ], 200);
    }

    public function createBrand(array $data)
    {
        DB::begintransaction();
        try {
            $brand = Brand::create($data);
            if ($data['image']) {
                $path = $data['image']->store('brands', 'public');
                $url = asset('storage/' . $path);
            }

            $brand->image_url = $url;
            $brand->image_path = $path;
            $brand->save();
            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'data' => $brand,
                ],
                200
            );
        } catch (\Exception $e) {
            $e->getMessage();
            DB::rollBack();
        }
    }

public function updateBrand($id, array $data)
{
    DB::beginTransaction();

    try {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        // Update text fields first
        $brand->update($data);

        // If there is image, handle it
        if (isset($data['image']) && $data['image']) {
            $path = $data['image']->store('brands', 'public');
            $url = asset('storage/' . $path);

            $brand->image_url = $url;
            $brand->image_path = $path;
            $brand->save();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $brand
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function deleteBrand($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $brand->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Brand deleted successfully',
                ],
                200
            );
        }
    }
}
