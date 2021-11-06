<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan("migrate");
        $this->artisan("db:seed");
    }

    /** @test */
    public function registerIfUserIsValid()
    {
        $data = [
            "name" => "first second",
            "email" => "first@email.com",
            "password" => "123123"
        ];

        $response = $this->post("api/user/register", $data);

        $response->assertCreated();
    }

    /** @test */
    public function returnErrorIfInvalidName()
    {
        $data = [
            "name" => "djkspdkaopaskdopdk asopdaskopdasdkopasdaopskdaopdka",
            "email" => "email@test.com",
            "password" => "password"
        ];

        $response = $this->post("api/user/register", $data);

        $response->assertInvalid(["name"]);
    }

    /** @test */
    public function returnErrorIfInvalidEmail()
    {
        $data = [
            "name" => "first second",
            "email" => "emaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaail@test.com",
            "password" => "password"
        ];

        $response = $this->post("api/user/register", $data);

        $response->assertInvalid(["email"]);
    }

    /** @test */
    public function returnErrorIfInvalidPassword()
    {
        $data = [
            "name" => "first second",
            "email" => "email@test.com",
            "password" => "passwordpassw"
        ];

        $response = $this->post("api/user/register", $data);

        $response->assertInvalid(["password"]);
    }

    /** @test */
    public function returnErrorIfTryToRegisterRepeatedEmail()
    {
        $data = [
            "name" => "first second",
            "email" => "admin@email.com",
            "password" => "123456"
        ];

        $response = $this->post("api/user/register", $data);

        $response->assertJsonFragment(["error" => "User already registered"]);
    }
}
