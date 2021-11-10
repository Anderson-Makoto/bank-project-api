<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\Filesystem;

class DepositCreateTest extends TestCase
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
    public function verify_if_save_deposit_pending_correctly()
    {
        $file = UploadedFile::fake()->image('check.png', 20, 20);

        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "description" => "check description",
            "value" => 100.75,
            "image" => $file
        ];


        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["customer"]["token"]
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        $response->assertSuccessful();
    }

    /** @test */
    public function verify_if_send_error_when_not_customer()
    {
        $file = UploadedFile::fake()->image('check.png', 20, 20);

        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "description" => "check description",
            "value" => 100.75,
            "image" => $file
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["admin"]["token"]
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        $response->assertJsonFragment(["data" => "You don't have customer's permissions"]);
    }

    /** @test */
    public function verify_if_send_error_if_file_is_not_image()
    {
        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "description" => "check description",
            "value" => 100.75,
            "image" => "not image file"
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["customer"]["token"]
        ];

        $response = $this->post("api/deposit/save", $data, $header);

        $response->assertJsonFragment([0 => "The image must be an image."]);
    }

    /** @test */
    public function verify_if_admin_can_change_deposits_status()
    {
        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["admin"]["token"]
        ];

        $response = $this->get("api/deposit/changeDepositStatus/1/2/2", $header);

        $response->assertSuccessful();
    }
}
