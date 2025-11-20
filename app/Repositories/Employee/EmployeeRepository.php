<?php

namespace App\Repositories\Employee;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function getEmployees(Request $request)
    {
        $employees = Employee::latest()->paginate(config('app.per_page'));
        return response()->json(
            [
                'success' => true,
                'data' => $employees,
            ],
            200
        );
    }

    public function getEmployeeById($id)
    {
        $employee = Employee::find($id);
        return response()->json([
            'success' => true,
            'data' => $employee,
        ], 200);
    }

    public function createEmployee(array $data)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::create($data);
            if ($employee) {
                if ($data['image']) {
                    $path = $data['image']->store('employees', 'public');
                    $url = asset('storage/' . $path);
                }

                $employee->image_url = $url;
                $employee->image_path = $path;
                $employee->save();
            }
            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'data' => $employee,
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Employee creation failed',
                'error' => $e->getMessage(),
            ], 500);
            throw $e;
        }
    }

    public function updateEmployee($id, array $data)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        // Handle image update
        if (isset($data['image']) && $data['image']) {

            // Delete old image from storage
            if ($employee->image_path && \Storage::disk('public')->exists($employee->image_path)) {
                \Storage::disk('public')->delete($employee->image_path);
            }

            // Save new image
            $path = $data['image']->store('employees', 'public');
            $url  = asset('storage/' . $path);

            // Overwrite in database
            $employee->image_url  = $url;
            $employee->image_path = $path;
        }

        // Update other fields (except image)
        $employee->update(collect($data)->except('image')->toArray());

        return response()->json([
            'success' => true,
            'data' => $employee
        ], 200);
    }


    public function deleteEmployee($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            // Delete image from storage
            if ($employee->image_path && \Storage::disk('public')->exists($employee->image_path)) {
                \Storage::disk('public')->delete($employee->image_path);
            }

            $employee->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Employee deleted successfully',
                ],
                200
            );
        }
    }

    public function toggleBan($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->is_ban = !$employee->is_ban;
        }
        $employee->save();
        
    }

    public function toggleVerify($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->is_verified = !$employee->is_verified;
        }
        $employee->save();
    }
}
