<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan("migrate");
        $this->artisan("db:seed");
        $this->artisan('db:seed', ['--class' => 'PopulateTestDb']);
    }

    /** @test */
    public function verify_if_login_user_correctly()
    {
        $data = [
            "email" => "first@test.com",
            "password" => "123456"
        ];

        $response = $this->post("api/user/login", $data);

        $response->assertJsonFragment(["email" => "first@test.com"]);
    }

    /** @test */
    public function verify_if_send_error_when_email_not_found()
    {
        $data = [
            "email" => "newEmail@test.com",
            "password" => "123456"
        ];

        $response = $this->post("api/user/login", $data);

        $response->assertJsonFragment(["description" => "User not found"]);
    }

    /** @test */
    public function verify_if_send_error_when_passwords_mismatches()
    {
        $data = [
            "email" => "first@test.com",
            "password" => "wrongPass"
        ];

        $response = $this->post("api/user/login", $data);

        $response->assertJsonFragment(["description" => "Passwords do not match"]);
    }

    /** @test */
    public function verify_if_send_error_when_without_password()
    {
        $data = [
            "email" => "first@test.com"
        ];

        $response = $this->post("api/user/login", $data);

        $response->assertJsonFragment([0 => "The password field is required."]);
    }

    /** @test */
    public function verify_if_send_error_when_without_email()
    {
        $data = [
            "password" => "123456"
        ];

        $response = $this->post("api/user/login", $data);

        $response->assertJsonFragment([0 => "The email field is required."]);
    }

    /** @test */
    public function verify_if_send_error_when_wrong_email_length()
    {
        $data = [
            "email" => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@test.com",
            "password" => "123456"
        ];

        $response = $this->post("api/user/login", $data);

        $response->assertJsonFragment([0 => "The email must not be greater than 50 characters."]);
    }
}
