<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;

class RetrieveDepositsTest extends TestCase
{
    private $admin, $customer;
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan("migrate");
        $this->artisan("db:seed");
        $this->artisan('db:seed', ['--class' => 'PopulateTestDb']);

        $this->simulateUserLogged();
        $this->generateDeposits();
    }

    private function simulateUserLogged()
    {
        $loginData = [
            "email" => "first@test.com",
            "password" => "123456"
        ];

        $loginUser = $this->post("api/user/login", $loginData);

        $this->customer["token"] = "Bearer " . $loginUser->decodeResponseJson()["data"]["token"];
        $this->customer["id"] = $loginUser->decodeResponseJson()["data"]["user"]["id"];

        $loginData = [
            "email" => "admin@email.com",
            "password" => "123123"
        ];

        $loginUser = $this->post("api/user/login", $loginData);

        $this->admin["token"] = "Bearer " . $loginUser->decodeResponseJson()["data"]["token"];
        $this->admin["id"] = $loginUser->decodeResponseJson()["data"]["user"]["id"];
    }

    private function generateDeposits()
    {
        Storage::fake("fakeDir");
        $file = UploadedFile::fake()->image('check.png', 20, 20);

        $data = [
            "user_fk" => 3,
            "description" => "check description",
            "value" => 100.75,
            "image" => $file
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->customer["token"]
        ];

        $this->post("api/deposit/save", $data, $header);

        $file = UploadedFile::fake()->image('check2.png', 20, 20);

        $data = [
            "user_fk" => 3,
            "description" => "check 2 description",
            "value" => 200.75,
            "image" => $file
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->customer["token"]
        ];

        $this->post("api/deposit/save", $data, $header);
    }

    /** @test */
    public function verify_if_get_deposit_by_status_and_month()
    {
        $data = [
            "user_fk" => 3,
            "deposit_status_fk" => 1,
            "year" => date("Y"),
            "month" => date("m")
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->customer["token"]
        ];

        $response = $this->post("api/deposit/getUserDepositsByStatusAndByMonth", $data, $header);

        $response->assertJsonCount(2, "data");
    }

    /** @test */
    public function verify_if_send_error_if_not_customer()
    {
        $data = [
            "user_fk" => 3,
            "deposit_status_fk" => 1,
            "year" => date("Y"),
            "month" => date("m")
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->admin["token"]
        ];

        $response = $this->post("api/deposit/getUserDepositsByStatusAndByMonth", $data, $header);

        dd($this->admin, $this->customer);
    }
}
