<?php 

namespace App\Repositories\Role;

use Illuminate\Http\Request;

interface RoleRepositoryInterface
{
    public function getRoles(Request $request);

    public function getRoleById($id);

    public function createRole(array $data);

    public function updateRole($id, array $data);

    public function deleteRole($id);
}