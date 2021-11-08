<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DepositCreateTest extends TestCase
{
    private $customerToken = "";
    private $adminToken = "";
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

        $this->customerToken = "Bearer " . $loginUser->decodeResponseJson()["data"]["token"];

        $loginData = [
            "email" => "admin@email.com",
            "password" => "123123"
        ];

        $loginUser = $this->post("api/user/login", $loginData);

        $this->adminToken = "Bearer " . $loginUser->decodeResponseJson()["data"]["token"];
    }

    /** @test */
    public function verify_if_save_deposit_pending_correctly()
    {
        Storage::fake("img/checks");
        $file = UploadedFile::fake()->image('check.png', 20, 20);

        $data = [
            "user_fk" => 3,
            "description" => "check description",
            "value" => 100.75,
            "image" => $file
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->customerToken
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        $response->assertJsonFragment(["fk_deposit_status" => 1]);
    }

    /** @test */
    public function verify_if_send_error_when_not_customer()
    {
        Storage::fake("img/checks");
        $file = UploadedFile::fake()->image('check.png', 20, 20);

        $data = [
            "user_fk" => 3,
            "description" => "check description",
            "value" => 100.75,
            "image" => $file
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->adminToken
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        $response->assertJsonFragment(["data" => "You don't have customer's permissions"]);
    }

    /** @test */
    public function verify_if_send_error_if_file_is_not_image()
    {
        Storage::fake("img/checks");

        $data = [
            "user_fk" => 3,
            "description" => "check description",
            "value" => 100.75,
            "image" => "not image file"
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->customerToken
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        $response->assertJsonFragment([0 => "The image must be an image."]);
    }
}
