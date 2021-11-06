<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PopulateTestDb extends Seeder
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
                "id" => 3,
                "name" => "first",
                "email" => "first@test.com",
                "password" => Hash::make("123456"),
                "is_admin" => false
            ],
            [
                "id" => 4,
                "name" => "second",
                "email" => "second@test.com",
                "password" => Hash::make("654321"),
                "is_admin" => false
            ],
            [
                "id" => 5,
                "name" => "third",
                "email" => "third@test.com",
                "password" => Hash::make("321321"),
                "is_admin" => false
            ]
        ]);
    }
}
