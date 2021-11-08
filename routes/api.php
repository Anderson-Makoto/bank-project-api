<?php

use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("user/register", [UserController::class, "registerUser"]);
Route::post("user/login", [UserController::class, "loginUser"]);

route::middleware(["auth:sanctum"])->group(function () {
    Route::get("user/logout/{id}", [UserController::class, "logoutUser"]);
    Route::get("user/getBalance", [UserController::class, "getUserBalance"])->middleware("isCustomer");
    Route::post("deposit/save", [DepositController::class, "store"])->middleware("isCustomer");
    Route::post("deposit/getByUserAndStatus", [DepositController::class, "getUserDepositsByStatus"])->middleware("isCustomer");
    Route::post("deposit/getUserDepositsByStatusAndByMonth", [DepositController::class, "getUserDepositsByStatusAndByMonth"])->middleware("isCustomer");
    Route::get("deposit/getPendingDeposits", [DepositController::class, "getCustomersPendingDeposits"])->middleware("isAdmin");
    Route::get("deposit/getDepositDetailsbyId/{depositId}/{customerId}", [DepositController::class, "getPendingDepositDetails"])->middleware("isAdmin");
    Route::get("deposit/changeDepositStatus/{depositId}/{status}/{customerId}", [DepositController::class, "changeDepositStatus"])->middleware("isAdmin");
    Route::post("purchase/save", [PurchaseController::class, "store"])->middleware("isCustomer");
    route::get("purchase/getAll/{month}/{year}", [PurchaseController::class, "getAllPurchasesByMonth"])->middleware("isCustomer");
});
