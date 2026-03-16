<?php 

namespace App\Repositories\Customer;

use Illuminate\Http\Request;

interface CustomerRepositoryInterface
{
    public function getCustomers(Request $request);

    public function registerCustomer(Request $request);

    public function getProfile();

    public function updateProfile(Request $request);

    public function favProductsByCustomer();

    public function saveProductsByCustomer();

}