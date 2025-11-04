<?php 

namespace App\Repositories\Customer;

use Illuminate\Http\Request;

interface CustomerRepositoryInterface
{
    public function getCustomers(Request $request);
}