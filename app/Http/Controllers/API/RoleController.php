<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    protected $roleRepo;
    public function __construct(\App\Repositories\Role\RoleRepositoryInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    public function getRoles(Request $request)
    {
        return $this->roleRepo->getRoles($request);
    }

    public function getRoleById($id)
    {
        return $this->roleRepo->getRoleById($id);
    }

    public function createRole(Request $request)
    {
        $data = $request->all();
        return $this->roleRepo->createRole($data);
    }

    public function updateRole(Request $request, $id)
    {
        $data = $request->all();
        return $this->roleRepo->updateRole($id, $data);
    }

    public function deleteRole($id)
    {
        return $this->roleRepo->deleteRole($id);
    }
}
