<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;


class RetrieveDepositsTest extends TestCase
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
    public function verify_if_get_deposit_by_status_and_month()
    {
        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "deposit_status_fk" => [1],
            "year" => 2021,
            "month" => 11
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["customer"]["token"]
        ];

        $response = $this->post("api/deposit/getUserDepositsByStatusAndByMonth", $data, $header);

        $response->assertJsonCount(1, "data");
    }

    /** @test */
    public function verify_if_send_error_if_not_customer()
    {
        $data = [
            "user_fk" => $this->populatedData["users"]["customer"]["data"]["id"],
            "deposit_status_fk" => 1,
            "year" => 2021,
            "month" => 11
        ];

        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["admin"]["token"]
        ];

        $response = $this->post("api/deposit/getUserDepositsByStatusAndByMonth", $data, $header);

        $response->assertJsonFragment(["data" => "You don't have customer's permissions"]);
    }
}
