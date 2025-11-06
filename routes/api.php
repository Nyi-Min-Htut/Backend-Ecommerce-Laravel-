<?php

use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/customers',[CustomerController::class,'getCustomers']);

Route::post('customers/login',[CustomerAuthController::class, 'login']);
Route::post('customers/logout',[CustomerAuthController::class,'logout']);

Route::get('categories',[CategoryController::class,'getCategories']);
Route::get('categories/{id}',[CategoryController::class,'getCategoryById']);
Route::post('categories',[CategoryController::class,'createCategory']);
Route::post('categories/{id}',[CategoryController::class,'updateCategory']);
Route::delete('categories/{id}',[CategoryController::class,'deleteCategory']);

Route::get('brands',[BrandController::class,'getBrands']);
Route::get('brands/{id}',[BrandController::class,'getBrandById']);
Route::post('brands',[BrandController::class,'createBrand']);
Route::post('brands/{id}',[BrandController::class,'updateBrand']);
Route::delete('brands/{id}',[BrandController::class,'deleteBrand']);