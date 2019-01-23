<?php

namespace Tests\Feature;

use App\User;

class UserTest
{
    public function test_user_can_see_user() // phpcs:disable
    {
        $response = $this->get('/userr');
        $response->assertStatus(201);
    }
}