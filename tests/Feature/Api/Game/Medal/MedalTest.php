<?php

namespace Tests\Feature\Api\Game\Medal;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Actor;
use App\Models\Medal;
use Illuminate\Http\UploadedFile;

class MedalTest extends TestCase
{
    /**
     * Usuário com nível de administrador
     *
     * @var User
     */
    protected $administrator;

    /**
     * Usuário com nível de design
     *
     * @var User
     */
    protected $design;

    /**
     * Usuário com nível de player
     *
     * @var User
     */
    protected $player;

    /**
     * Configurar usuário administrador
     *
     * @return void
     */
    private function setUpUserAdministrator()
    {
        // Cria um usuário aleatório com nível de administrador
        $this->administrator = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->administrator->toArray());
        $actor = factory(Actor::class)->create([
            'user_id' => $this->administrator->id,
            'is_administrator' => true
        ]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);
    }

    /**
     * Configurar usuário design
     *
     * @return void
     */
    private function setUpUserDesign()
    {
        // Cria um usuário aleatório com nível de design
        $this->design = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->design->toArray());
        $actor = factory(Actor::class)->create([
            'user_id' => $this->design->id,
            'is_administrator' => false,
            'is_design' => true,
            'is_player' => false
        ]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertFalse($actor->is_administrator);
        $this->assertTrue($actor->is_design);
        $this->assertFalse($actor->is_player);
    }

    /**
     * Configurar usuário jogador
     */
    private function setUpUserPlayer()
    {
        // Cria um usuário aleatório com nível de player
        $this->player = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->player->toArray());
        $actor = factory(Actor::class)->create([
            'user_id' => $this->player->id,
            'is_administrator' => false,
            'is_design' => false,
            'is_player' => true
        ]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertFalse($actor->is_administrator);
        $this->assertFalse($actor->is_design);
        $this->assertTrue($actor->is_player);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUserAdministrator();
        $this->setUpUserDesign();
        $this->setUpUserPlayer();
    }

    /**
     * Testa se um usuário anônimo pode gerenciar medalhas de um jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_medal_resource_in_api()
    {
        // Cria um jogo no banco de dados.
        $gameOne = factory(Game::class)->create();
        // Crima uma medalha para vincular ao jogo.
        $gameOneMedalOne = factory(Medal::class)->create();

        // CREATE
        $data = $gameOneMedalOne->toArray();
        $data['medal_id'] = $data['id'];
        $response = $this->json('post', '/api/game/' . $gameOne->id . '/medal', $data);
        $response->assertStatus(401);
        // INDEX
        $response = $this->json('get', '/api/game/' . $gameOne->id . '/medal');
        $response->assertStatus(401);
        // DELETE
        // Vincula a medalha ao jogo para testar sua desvinculação
        $gameOne->medals()->attach($gameOneMedalOne);
        $response = $this->json('delete', '/api/game/' . $gameOne->id . '/medal/' . $gameOneMedalOne->id);
        $response->assertStatus(401);
    }

    /**
     * Testa se um usuário administrador pode gerenciar medalhas de um jogo na API.
     *
     * @return void
     */
    public function test_administrador_can_manage_game_medal_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->administrator->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Uma medalha
        $medalOne = factory(Medal::class)->create();

        // CREATE
        $data = $medalOne->toArray();
        $data['medal_id'] = $data['id'];
        // Verifica se o usuário pode vincular medalha ao próprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne->id . '/medal', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Verifica se o usuário pode vincular medalha ao jogo de outro proprietário.
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo->id . '/medal', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        /** Não aplicado ainda
        // INDEX
        // Verifica se o usuário pode visualizar medalhas do próprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "name", "description"]
            ]
        ]);
        // Verifica se o usuário pode visualizar medalhas do jogo de outro proprietário.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "name", "description"]
            ]
        ]); Não aplicado ainda */

        // Cria e vincula a medalha ao jogo para teste de desvinculação de medalha do jogo.
        $gameOneMedalOne = factory(Medal::class)->create();
        $gameTwoMedalOne = factory(Medal::class)->create();
        $gameOne->medals()->attach($gameOneMedalOne);
        $gameTwo->medals()->attach($gameTwoMedalOne);

        // DELETE
        // Verifica se o usuário pode desvincular medalha do pŕoprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne->id . '/medal/' . $gameOneMedalOne->id);
        $response->assertStatus(204);
        // Verifica se o usuário pode desvincular medalha do jogo de outro proprietário
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/medal/' . $gameTwoMedalOne->id);
        $response->assertStatus(204);
    }

    /**
     * Testa se um usuário design pode gerenciar medalhas de um jogo na API.
     *
     * @return void
     */
    public function test_design_can_manage_game_medal_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Uma medalha
        $medalOne = factory(Medal::class)->create();

        // CREATE
        $data = $medalOne->toArray();
        $data['medal_id'] = $data['id'];
        // Verifica se o usuário pode vincular medalha ao próprio jogo.
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameOne->id . '/medal', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Verifica se o usuário pode vincular medalha ao jogo de outro proprietário.
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameTwo->id . '/medal', $data);
        $response->assertStatus(403);

        /** Não aplicado ainda
        // INDEX
        // Verifica se o usuário pode visualizar medalhas do próprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
        "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
        "links" => ["first", "last", "prev", "next"], "data" => [
        "*" => ["id", "name", "description"]
        ]
        ]);
        // Verifica se o usuário pode visualizar medalhas do jogo de outro proprietário.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
        "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
        "links" => ["first", "last", "prev", "next"], "data" => [
        "*" => ["id", "name", "description"]
        ]
        ]); Não aplicado ainda */

        // Cria e vincula a medalha ao jogo para teste de desvinculação de medalha do jogo.
        $gameOneMedalOne = factory(Medal::class)->create();
        $gameTwoMedalOne = factory(Medal::class)->create();
        $gameOne->medals()->attach($gameOneMedalOne);
        $gameTwo->medals()->attach($gameTwoMedalOne);

        // DELETE
        // Verifica se o usuário pode desvincular medalha do pŕoprio jogo
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameOne->id . '/medal/' . $gameOneMedalOne->id);
        $response->assertStatus(204);
        // Verifica se o usuário pode desvincular medalha do jogo de outro proprietário
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/medal/' . $gameTwoMedalOne->id);
        $response->assertStatus(403);
    }
}