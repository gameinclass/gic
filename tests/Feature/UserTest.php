<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    public function test_user_can_see_user() // phpcs:disable
    {
        $response = $this->json('GET', '/user');
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            "total",
            "per_page",
            "current_page",
            "last_page",
            "first_page_url",
            "last_page_url",
            "next_page_url",
            "prev_page_url",
            "path",
            "from",
            "to",
            "data",
        ]);
    }
}