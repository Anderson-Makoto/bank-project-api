<?php

namespace App\Repositories;

use App\Exceptions\SQLException;
use App\Exceptions\UnauthorizedRequest;
use App\Interfaces\Repositories\IUserRepository;
use App\Models\Deposit;
use App\Models\Purchase;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{
    public function saveUser(Request $request)
    {
        $this->verifyIfUserAlreadyExists($request);

        DB::beginTransaction();

        try {
            $user = User::create([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "password" => Hash::make($request->input("password"))
            ]);

            $token = $this->saveToken($user);

            DB::commit();

            return [
                "user" => $user,
                "token" => $token
            ];
        } catch (Exception $e) {
            DB::rollBack();

            throw new SQLException("Database problem", 500);
        }
    }

    private function verifyIfUserAlreadyExists(Request $request)
    {
        $user = $this->getUserByEmail($request);

        if ($user) {
            throw new SQLException("User already registered", 400);
        }
    }

    public function saveToken($user)
    {
        $token = $user->createToken("API token")->plainTextToken;

        return $token;
    }

    public function getUserByEmail(Request $request)
    {
        $user = User::where("email", $request->input("email"))->first();

        return $user;
    }

    public function deleteUserToken($id)
    {
        $this->verifyId($id);

        try {
            Auth::user()->tokens->each(function ($token, $key) {
                $token->delete();
            });

            return response()->json([
                "data" => "User logged out"
            ], 200);
        } catch (Exception) {
            throw new SQLException("Could not erase user token.", 500);
        }
    }

    private function verifyId($id)
    {
        if ($id != auth()->user()->id)
            throw new UnauthorizedRequest("Id doesn't match", 400);
    }

    public function getUserById($id)
    {
        try {
            $user = User::where("id", $id)->first();

            if (!$user)
                throw new Exception("User not found", 400);

            return $user;
        } catch (Exception $e) {
            throw new Exception("Could not retrieve user", 500);
        }
    }

    public function getUserBalance()
    {
        $id = auth()->user()->id;

        try {
            $totalDeposits = Deposit::where("fk_user", $id)
                ->where("fk_deposit_status", 2)
                ->sum("value");

            $totalPurchases = Purchase::where("fk_user", $id)
                ->sum("value");

            return $totalDeposits - $totalPurchases;
        } catch (Exception $e) {
            throw new SQLException("Could not get sum of deposits and sum of purchases from database", 500);
        }
    }
}
