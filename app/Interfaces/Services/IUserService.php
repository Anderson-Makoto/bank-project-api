<?php

namespace App\Interfaces\Services;

use Illuminate\Http\Request;

interface IUserService
{
    public function registerUser(Request $request);

    public function loginUser(Request $request);

    public function logoutUser($id);
}
