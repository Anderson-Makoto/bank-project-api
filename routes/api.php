<?php

use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\IsCustomer;
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
    Route::post("deposit/save", [DepositController::class, "store"])->middleware("isCustomer");
    Route::post("deposit/getByUserAndStatus", [DepositController::class, "getUserDepositsByStatus"])->middleware("isCustomer");
    Route::post("deposit/getUserDepositsByStatusAndByMonth", [DepositController::class, "getUserDepositsByStatusAndByMonth"])->middleware("isCustomer");
    Route::get("deposit/getPendingDeposits", [DepositController::class, "getCustomersPendingDeposits"])->middleware("isAdmin");
    Route::get("deposit/getDepositDetailsbyId/{depositId}/{customerId}", [DepositController::class, "getPendingDepositDetails"])->middleware("isAdmin");
});
