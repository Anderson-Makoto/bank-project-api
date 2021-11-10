<?php

namespace Tests;

use App\Models\Deposit;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function populateDb()
    {
        $this->artisan("migrate");
        $this->artisan("db:seed");

        $usersFormated = [
            "admin" => $this->simulateUserLogged(["email" => "admin@email.com"]),
            "customer" => $this->simulateUserLogged($this->generateUsers()->getAttributes())
        ];

        return [
            "users" => $usersFormated,
            "deposits" => $this->generateDeposits(),
            "purchases" => $this->generatePurchases()
        ];
    }

    private function generateUsers()
    {
        return User::factory()->state(new Sequence(
            [
                "is_admin" => false,
                "password" => Hash::make("123123"),
                "email" => "customer@email.com",
                "id" => 2
            ]
        ))->create();
    }


    private function generateDeposits()
    {
        return Deposit::factory()->count(3)->state(new Sequence(
            [
                "fk_deposit_status" => 1,
                "fk_user" => 2,
                "updated_at" => "2021-11-01 12:00:00"
            ],
            [
                "fk_deposit_status" => 2,
                "fk_user" => 2,
                "updated_at" => "2021-11-10 13:00:00"
            ],
            [
                "fk_deposit_status" => 3,
                "fk_user" => 2,
                "updated_at" => "2021-10-01 12:00:00"
            ]
        ))->create();
    }

    private function generatePurchases()
    {
        return Purchase::factory()->count(3)->state(new Sequence(
            [
                "fk_user" => 2,
                "purchase_date" => "2021-11-04 00:00:00"
            ],
            [
                "fk_user" => 2,
                "purchase_date" => "2021-11-25 13:00:00"
            ],
            [
                "fk_user" => 2,
                "purchase_date" => "2021-10-04 00:00:00"
            ]
        ))->create();
    }

    private function simulateUserLogged($users)
    {
        $loginData = [
            "email" => $users["email"],
            "password" => "123123"
        ];

        $loginUser = $this->post("api/user/login", $loginData);

        return [
            "data" => $users,
            "token" => "Bearer " . $loginUser->decodeResponseJson()["data"]["token"]
        ];
    }
}
