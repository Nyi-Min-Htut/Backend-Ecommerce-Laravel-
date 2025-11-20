<?php 

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleRepository implements RoleRepositoryInterface
{
    public function getRoles(Request $request)
    {
        $roles = Role::latest()->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $roles,
            ],
            200
        );
    }

    public function getRoleById($id)
    {
        $role = Role::find($id);
        return response()->json([
            'success' => true,
            'data' => $role,
        ], 200);
    }

    public function createRole(array $data)
    {
        $role = Role::create($data);
        return response()->json([
            'success' => true,
            'data' => $role,
        ], 200);
    }

    public function updateRole($id, array $data)
    {
        $role = Role::find($id);
        if ($role) {
            $role->update($data);
            return response()->json(
                [
                    'success' => true,
                    'data' => $role,
                ],
                200
            );
        }
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully',
            ], 200);
        }
    }

}