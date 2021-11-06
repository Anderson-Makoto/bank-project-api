<?php

namespace App\Interfaces\Repositories;

use Illuminate\Http\Request;

interface IUserRepository
{
    public function saveUser(Request $request);

    public function getUserByEmail(Request $request);

    public function saveToken($user);

    public function deleteUserToken($id);

    public function getUserById($id);

    public function getUserBalance();
}
