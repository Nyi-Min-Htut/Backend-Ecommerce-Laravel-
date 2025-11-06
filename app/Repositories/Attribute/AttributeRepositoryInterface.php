<?php 

namespace App\Repositories\Attribute;

use Illuminate\Http\Request;

interface AttributeRepositoryInterface
{
    public function getAttributes(Request $request);

    public function createAttribute(array $data);

    public function updateAttribute($id, array $data);

    public function deleteAttribute($id);
}