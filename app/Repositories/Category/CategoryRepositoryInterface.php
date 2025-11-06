<?php 

namespace App\Repositories\Category;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function getCategories(Request $request);

    public function getCategoryById($id);

    public function createCategory(array $data);

    public function updateCategory(array $data, $id);

    public function deleteCategory($id);

}