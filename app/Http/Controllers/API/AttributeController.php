<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    //
    protected $attributeRepo;
    public function __construct(AttributeRepositoryInterface $attributeRepo)
    {
        $this->attributeRepo = $attributeRepo;
    }

    public function getAttributes(Request $request)
    {
        return $this->attributeRepo->getAttributes($request);
    }

    public function getAttributeById($id)
    {
        return $this->attributeRepo->getAttributeById($id);
    }

    public function createAttribute(Request $request)
    {
        $data = $request->all();
        return $this->attributeRepo->createAttribute($data);
    }

    public function updateAttribute(Request $request, $id)
    {
        $data = $request->all();
        return $this->attributeRepo->updateAttribute($id, $data);
    }

    public function deleteAttribute($id)
    {
        return $this->attributeRepo->deleteAttribute($id);
    }

    public function attributeByCategoryId($id)
    {
        return $this->attributeRepo->attributeByCategoryId($id);
    }
}
