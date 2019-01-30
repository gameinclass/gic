<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_index_users()
    {
        $structure = [
            'meta' => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"],
            "data"
        ];
        $response = $this->json('get', '/api/user');
        $response->assertStatus(200);
        $response->assertJsonStructure($structure);
    }
}
