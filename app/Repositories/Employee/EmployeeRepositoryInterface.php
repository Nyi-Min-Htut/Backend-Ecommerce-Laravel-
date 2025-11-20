<?php 

namespace App\Repositories\Employee;

use Illuminate\Http\Request;

interface EmployeeRepositoryInterface
{
    public function getEmployees(Request $request);

    public function getEmployeeById($id);

    public function createEmployee(array $data);

    public function updateEmployee($id, array $data);

    public function deleteEmployee($id);

    public function toggleBan($id);

    public function toggleVerify($id);
}