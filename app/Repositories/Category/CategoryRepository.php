<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(Request $request)
    {
        $categories = Category::with('attributes')->latest()->paginate(config('app.per_page'));
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
        $category = Category::with('attributes')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category,
        ], 200);
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

            if($data['image'])
            {
                $path = $data['image']->store('category','public');
                $url = asset('storage/'.$path);
            }

            $category->image_url = $url;
            $category->image_path = $path;

            $category->save();
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
    if($data['image'])
    {
        $path = $data['image']->store('category','public');
        $url = asset('storage/'.$path);
        $category->image_url = $url;
        $category->image_path = $path;
        $category->save();
    }
    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'Category not found'
        ], 404);
    }

    // Update category basic info
    $category->update([
        'name' => $data['name'],
        'description' => $data['description']
    ]);

    // Handle attributes
    if (isset($data['attribute_ids'])) {
        $attributeIds = json_decode($data['attribute_ids']);
        
        // Sync all attributes at once (removes old ones and adds new ones)
        $category->attributes()->sync($attributeIds);
    } else {
        // If no attributes are provided, remove all existing attributes
        $category->attributes()->detach();
    }

    // Load the updated category with attributes
    $category->load('attributes');

    return response()->json([
        'success' => true,
        'data' => $category,
    ], 200);
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
