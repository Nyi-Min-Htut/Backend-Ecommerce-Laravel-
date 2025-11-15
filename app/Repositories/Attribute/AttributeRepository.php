<?php

namespace App\Repositories\Attribute;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;

class AttributeRepository implements AttributeRepositoryInterface
{
    public function getAttributes(Request $request)
    {
        $attributes = Attribute::orderBy('created_at', 'desc')->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $attributes,
            ],
            200
        );
    }

    public function createAttribute(array $data)
    {
        $attribute = Attribute::create($data);
        return response()->json([
            'success' => true,
            'data' => $attribute,
        ], 200);
    }

    public function updateAttribute($id, array $data)
    {
        $attribute = Attribute::find($id);
        if ($attribute) {
            $attribute->update($data);
            return response()->json(
                [
                    'success' => true,
                    'data' => $attribute,
                ],
                200
            );
        }
    }

    public function deleteAttribute($id)
    {
        $attribute = Attribute::find($id);
        if ($attribute) {
            $attribute->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Attribute deleted successfully',
                ],
                200
            );
        }
    }

    public function attributeByCategoryId(int $id)
    {
        $category = Category::find($id);
        return response()->json([
            'success'=>true,
            'data'=> $category->attributes
        ]);
    }
}
