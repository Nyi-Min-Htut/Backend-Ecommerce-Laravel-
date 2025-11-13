<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(Request $request)
    {
        $categories = Category::latest()->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $categories,
            ],
            200
        );
    }

    public function getCategoryById($id)
    {
        $category = Category::find($id);
        return response()->json(
            [
                'success' => true,
                'data' => $category,
            ],
            200
        );
    }

    public function createCategory(array $data)
    {
        DB::beginTransaction();
        try {
            $category = Category::create($data);
            if ($data['attribute_ids']) {
                $attributeIds = json_decode($data['attribute_ids']);
                foreach ($attributeIds as $attr) {
                    $category->attributes()->attach($attr);
                }
            }
            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'data' => $category,
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCategory(array $data, $id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->update($data);
            return response()->json(
                [
                    'success' => true,
                    'data' => $category,
                ],
                200
            );
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Category deleted successfully',
                ],
                200
            );
        }
    }
}
