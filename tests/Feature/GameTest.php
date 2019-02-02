<?php

namespace Tests\Feature;

use Tests\TestCase;

class GameTest extends TestCase
{
    /**
     * Verifica se um usuário (anônimo) pode listar todos os jogos.
     *
     * @return void
     */
    public function test_it_can_index_users()
    {
        // Requisição, resposta e asserções
        $response = $this->json('get', '/api/game');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id", "title", "description"]
            ]
        ]);
    }
}
