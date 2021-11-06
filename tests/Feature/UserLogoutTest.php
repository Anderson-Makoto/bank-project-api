<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLogoutTest extends TestCase
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
    public function verify_if_logout_user_correctly()
    {
        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->token
        ];

        $response = $this->get("api/user/logout/3", $header);

        $response->assertJson(["data" => "User logged out."]);
    }

    /** @test */
    public function verify_if_send_error_when_token_is_invalid()
    {
        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => "Bearer fje8902jf8e90jwf8w9fjw890fj48903jf84fn47839nfgr8beg8r0ej4894"
        ];

        $response = $this->get("api/user/logout/3", $header);

        $response->assertJson(["message" => "Unauthenticated."]);
    }

    /** @test */
    public function verify_if_user_already_logged_out()
    {
        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => "Bearer fje8902jf8e90jwf8w9fjw890fj48903jf84fn47839nfgr8beg8r0ej4894"
        ];

        $response = $this->get("api/user/logout/4", $header);

        $response->assertJson(["message" => "Unauthenticated."]);
    }

    /** @test */
    public function verify_if_send_error_when_id_not_matches()
    {
        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->token
        ];

        $response = $this->get("api/user/logout/4", $header);

        $response->assertJson(["description" => "Id doesn't match"]);
    }
}
