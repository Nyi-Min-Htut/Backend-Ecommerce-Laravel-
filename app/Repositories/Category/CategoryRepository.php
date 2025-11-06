<?php 

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(Request $request)
    {
        $categories = Category::latest()->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $categories,
            ],200);
}

    public function getCategoryById($id)
    {
        $category = Category::find($id);
        return response()->json(
            [
                'success' => true,
                'data' => $category,
            ],200);
    }

    public function createCategory(array $data)
    {
        $category = Category::create($data);
        return response()->json(
            [
                'success' => true,
                'data' => $category,
            ],200);
    }

    public function updateCategory(array $data, $id)
    {
        $category = Category::find($id);
        if($category)
        {
            $category->update($data);
            return response()->json(
                [
                    'success' => true,
                    'data' => $category,
                ],200);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if($category)
        {
            $category->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Category deleted successfully',
                ],200);
        }
    }
}