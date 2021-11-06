<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Testing\File;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DepositCreateTest extends TestCase
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

    /** @test */
    public function verify_if_save_deposit_pending_correctly()
    {
        // Storage::fake("img/checks");
        // $file = UploadedFile::fake()->image('check.jpeg', 20, 20);
        // $file = File::create("check.jpeg", 150)->image("check.jpeg");

        $data = [
            "user_fk" => 3,
            "description" => "check description",
            "value" => 100.75,
            "check_img" => imagecreate(50, 50)
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->token
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        dd($response->decodeResponseJson());
    }

    public function verify_if_send_error_when_not_customer()
    {
    }
}
