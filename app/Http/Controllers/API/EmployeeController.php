<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    protected $employeeRepo;
    public function __construct(\App\Repositories\Employee\EmployeeRepositoryInterface $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function getEmployees(Request $request)
    {
        return $this->employeeRepo->getEmployees($request);
    }

    public function getEmployeeById($id)
    {
        return $this->employeeRepo->getEmployeeById($id);
    }

    public function createEmployee(Request $request)
    {
        $data = $request->all();
        return $this->employeeRepo->createEmployee($data);
    }

    public function updateEmployee(Request $request, $id)
    {
        $data = $request->all();
        return $this->employeeRepo->updateEmployee($id, $data);
    }

    public function deleteEmployee($id)
    {
        return $this->employeeRepo->deleteEmployee($id);
    }

    public function toggleBan($id)
    {
        return $this->employeeRepo->toggleBan($id);
    }

    public function toggleVerify($id)
    {
        return $this->employeeRepo->toggleVerify($id);
    }




}
