<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("user")->insert([
            [
                "id" => 1,
                "name" => "admin",
                "email" => "admin@email.com",
                "password" => Hash::make("123123"),
                "is_admin" => true
            ]
        ]);
    }
}
