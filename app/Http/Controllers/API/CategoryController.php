<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    protected $cateRepo;
    public function __construct(CategoryRepositoryInterface $cateRepo)
    {
        $this->cateRepo = $cateRepo;
    }

    public function getCategories(Request $request)
    {
        $categories = $this->cateRepo->getCategories($request);
        return $categories;
    }

    public function getCategoryById($id)
    {
        $category = $this->cateRepo->getCategoryById($id);
        return $category;
    }

    public function createCategory(Request $request)
    {
        $data = $request->all();
        $category = $this->cateRepo->createCategory($data);
        return $category;
    }

    public function updateCategory(Request $request, $id)
    {
        $data = $request->all();
        $category = $this->cateRepo->updateCategory($data, $id);
        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->cateRepo->deleteCategory($id);
        return $category;
    }
}
