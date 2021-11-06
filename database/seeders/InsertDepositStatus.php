<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InsertDepositStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("deposit_status")->insert([
            [
                "id" => 1,
                "status" => "pending"
            ],
            [
                "id" => 2,
                "status" => "accepted"
            ],
            [
                "id" => 3,
                "status" => "rejected"
            ]
        ]);
    }
}
