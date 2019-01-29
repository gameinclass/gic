<?php

namespace Tests\Feature;

use App\Models\Actor;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    public function test_user_can_index_users() // phpcs:disable
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

    public function test_user_can_create_user() // phpcs:disable
    {
        $user = factory(User::class)->create();
        $data = $user->toArray();
        $actor = factory(Actor::class)->create(["user_id" => $user->id]);
        $data["actor"] = $actor->toArray();
        $response = $this->json('POST', '/user', $data);
        $response->assertResponseStatus(201);
        $response->seeJsonEquals([
            "data" => $data
        ]);
    }
}