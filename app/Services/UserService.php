<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Exceptions\UnauthorizedRequest;
use App\Interfaces\Repositories\IUserRepository;
use Illuminate\Http\Request;
use App\Interfaces\Services\IUserService;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserService implements IUserService
{
    private $userRepository;

    public function __construct(IUserRepository $iUserRepository)
    {
        $this->userRepository = $iUserRepository;
    }

    public function registerUser(Request $request)
    {
        try {
            $userData = $this->userRepository->saveUser($request);

            return $userData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $user = $this->verifyIfUserIsValid($request);
            $token = $this->userRepository->saveToken($user);

            return [
                "user" => $user,
                "token" => $token
            ];
        } catch (Exception $e) {
            throw new MainException("Could not login user", $e->getCode(), $e->getMessage());
        }
    }

    private function verifyIfUserIsValid(Request $request)
    {
        $user = $this->userRepository->getUserByEmail($request);

        if ($user) {
            $this->verifyIfPasswordsMatches($request, $user->password);
            return $user;
        }
        throw new UnauthorizedRequest("User not found", 401);
    }

    private function verifyIfPasswordsMatches(Request $request, $password)
    {
        $passwordsMatch = Hash::check($request->input("password"), $password);

        if ($passwordsMatch) {
            return true;
        }
        throw new UnauthorizedRequest("Passwords do not match", 401);
    }

    public function logoutUser($id)
    {
        try {
            $this->userRepository->deleteUserToken($id);
        } catch (Exception $e) {
            throw new MainException("Could not logout user", $e->getCode(), $e->getMessage());
        }
    }
}
