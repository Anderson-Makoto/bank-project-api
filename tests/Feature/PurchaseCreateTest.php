<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

class PurchaseCreateTest extends TestCase
{
    use RefreshDatabase;
    private $populatedData;
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake("public/img/checks");

        $this->populatedData = $this->populateDb();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        (new Filesystem)->cleanDirectory('public/img/checks');
    }

    /** @test */
    public function verify_if_register_purchase()
    {
        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "description" => "new purchase",
            "value" => 200.55,
            "purchase_date" => "2021-01-01 00:00:00"
        ];


        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["customer"]["token"]
        ];

        $response = $this->post("api/purchase/save", $data, $header);

        $response->assertSuccessful();
    }

    /** @test */
    public function verify_if_send_error_when_request_data_is_invalid()
    {
        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "description" => "new purchase dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd",
            "value" => 200.55,
            "purchase_date" => "2021-01-01 00:00:00"
        ];


        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["customer"]["token"]
        ];

        $response = $this->post("api/purchase/save", $data, $header);

        $response->assertJsonFragment([0 => "The description must not be greater than 20 characters."]);
    }

    /** @test */
    public function verify_if_send_error_if_not_customer()
    {
        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "description" => "new purchase",
            "value" => 200.55,
            "purchase_date" => "2021-01-01 00:00:00"
        ];


        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["admin"]["token"]
        ];

        $response = $this->post("api/purchase/save", $data, $header);

        $response->assertJsonFragment(["data" => "You don't have customer's permissions"]);
    }
}
