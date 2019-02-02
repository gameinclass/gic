<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;

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

    /**
     * Verifica se um usuário (anônimo) pode criar um jogo.
     *
     * @return void
     */
    public function test_it_can_create_game()
    {
        // Fábrica falsa
        $game = factory(Game::class)->make();
        // Dados de requisição
        $data = $game->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('post', '/api/game', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
    }

    /**
     * Testa se um usuário (anônimo) pode editar um jogo.
     *
     * @return void
     */
    public function test_it_can_edit_game()
    {
        // Recupera um jogo aleatório
        $game = Game::get()->random();
        // Altera os dados do usuário
        $game->title = 'Outro título';
        $game->description = 'Outra descrição';
        // Dados da requisição
        $data = $game->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('put', '/api/user/' . $game->id, $data);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "title" => $data["title"],
            "description" => $data["description"],
        ]]);
    }

    /**
     * Testa se um usuário (anônimo) pode remover um jogo.
     *
     * @return void
     */
    public function test_it_can_destroy_game()
    {
        // Recupera um jogo aleatório
        $game = Game::get()->random();
        // Requisição, resposta e asserções
        $response = $this->json('delete', '/api/user/' . $game->id);
        $response->assertStatus(204);
    }
}
