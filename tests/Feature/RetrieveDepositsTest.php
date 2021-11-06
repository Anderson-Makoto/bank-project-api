<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrieveDepositsTest extends TestCase
{
    private $token = "";
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan("migrate");
        $this->artisan("db:seed");
        $this->artisan('db:seed', ['--class' => 'PopulateTestDb']);

        $this->simulateUserLogged();
    }

    private function simulateUserLogged()
    {
        $loginData = [
            "email" => "first@test.com",
            "password" => "123456"
        ];

        $loginUser = $this->post("api/user/login", $loginData);

        $this->token = "Bearer " . $loginUser->decodeResponseJson()["data"]["token"];
    }
}
