<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

class RetrievePurchaseTest extends TestCase
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
    public function verify_if_get_all_purchases()
    {
        $header = [
            "HTTP_ACCEPT" => "application/ld+json",
            "HTTP_AUTHORIZATION" => $this->populatedData["users"]["customer"]["token"]
        ];

        $response = $this->get("api/purchase/getAll/11/2021", $header);

        $response->assertJsonCount(2, "data");
    }
}
