<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Interfaces\Services\IUserService;
use Exception;

class UserController extends Controller
{

    private $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function registerUser(RegisterUserRequest $request)
    {
        $request->validated();

        try {
            $user = $this->userService->registerUser($request);

            return response()->json([
                "data" => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "code" => $e->getCode()
            ], 500);
        }
    }

    public function loginUser(LoginUserRequest $request)
    {
        $request->validated();

        try {
            $userData = $this->userService->loginUser($request);
            return response()->json([
                "data" => $userData
            ], 201);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }

    public function logoutUser($id)
    {
        try {
            $this->userService->logoutUser($id);

            return response()->json([
                "data" => "User logged out."
            ], 200);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }

    public function getUserBalance()
    {
        try {
            $balance = $this->userService->getUserBalance();

            return response()->json([
                "data" => $balance
            ], 200);
        } catch (MainException $e) {
            return $e->errorResponse();
        }
    }
}
