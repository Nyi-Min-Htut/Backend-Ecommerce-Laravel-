<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    protected $cRepo;

    public function __construct(CustomerRepositoryInterface $cRepo)
    {
        $this->cRepo = $cRepo;
    }

    public function getCustomers(Request $request)
    {
        $customers = $this->cRepo->getCustomers($request);
        return $customers;
    }

    public function registerCustomer(Request $request)
    {
        return $this->cRepo->registerCustomer($request);
       
    }

    public function getProfile()
    {
        return $this->cRepo->getProfile();
    }

    public function updateProfile(Request $request)
    {
        return $this->cRepo->updateProfile($request);
    }

    public function favProductsByCustomer()
    {
        return $this->cRepo->favProductsByCustomer();
    }

    public function saveProductsByCustomer()
    {
        return $this->cRepo->saveProductsByCustomer();
    }
}
