<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
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
     * Testa se um usuário anônimo pode gerenciar recurso de jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_medal_resource_in_api()
    {
        // Arquivo de imagem falsa com dimensão e tamanho aleatório.
        $image = UploadedFile::fake()->image('image.jpg', rand(100, 800), rand(100, 800))->size(rand(100, 800));
        // CREATE
        $data = factory(Medal::class)->make([
            'image' => $image
        ])->toArray();
        $response = $this->json('post', '/api/medal', $data);
        $response->assertStatus(401);

        // Cria um recurso no banco para testar o gerenciamento.
        $resource = factory(Medal::class)->create()->toArray();

        // INDEX
        $response = $this->json('get', '/api/medal');
        $response->assertStatus(401);
        // EDIT
        $resource['title'] = 'Teste de atualização do título';
        $resource['description'] = 'Teste de atualização de outra descrição';
        $response = $this->json('put', '/api/medal/' . $resource['id'], $resource);
        $response->assertStatus(401);
        // DELETE
        $response = $this->json('delete', '/api/medal/' . $resource['id']);
        $response->assertStatus(401);
    }

    /**
     * Testa se um usuário administrador pode gerenciar recurso de jogo na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_medal_resource_in_api()
    {
        // Arquivo de imagem falsa com dimensão e tamanho aleatório.
        $image = UploadedFile::fake()->image('image.jpg', rand(100, 800), rand(100, 800))->size(rand(100, 800));
        // CREATE
        $data = factory(Medal::class)->make([
            'image' => $image
        ])->toArray();
        $response = $this->actingAs($this->administrator, 'api')
            ->json('post', '/api/medal', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // Do usuário
        $owner = factory(Medal::class)->create(['user_id' => $this->administrator->id])->toArray();
        // De outros
        $other = factory(Medal::class)->create()->toArray();

        // INDEX
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "title", "description", "url"]
            ]
        ]);
        // EDIT
        // recurso do usuário
        $owner['title'] = 'Teste de atualização do título';
        $owner['description'] = 'Teste de atualização de outra descrição';
        $response = $this->actingAs($this->administrator)
            ->json('put', '/api/medal/' . $owner['id'], $owner);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "title" => $owner["title"],
            "description" => $owner["description"],
        ]]);
        // Recurso de outros
        $other['title'] = 'Teste de atualização do título';
        $other['description'] = 'Teste de atualização de outra descrição';
        $response = $this->actingAs($this->administrator)
            ->json('put', '/api/medal/' . $other['id'], $other);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "title" => $other["title"],
            "description" => $other["description"],
        ]]);
        // DELETE
        // Recurso do usuário
        $response = $this->actingAs($this->administrator, 'api')
            ->json('delete', '/api/medal/' . $owner['id']);
        $response->assertStatus(204);
        // Recurso de outros
        $response = $this->actingAs($this->administrator, 'api')
            ->json('delete', '/api/medal/' . $other['id']);
        $response->assertStatus(204);
    }

    /**
     * Testa se um usuário design pode gerenciar recurso de usuário na API.
     *
     * @return void
     */
    public function test_design_can_manage_medal_resource_in_api()
    {
        // Arquivo de imagem falsa com dimensão e tamanho aleatório.
        $image = UploadedFile::fake()->image('image.jpg', rand(100, 800), rand(100, 800))->size(rand(100, 800));
        // CREATE
        $data = factory(Medal::class)->make([
            'image' => $image
        ])->toArray();
        $response = $this->actingAs($this->design, 'api')
            ->json('post', '/api/medal', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // Do usuário
        $owner = factory(Medal::class)->create(['user_id' => $this->design->id])->toArray();
        // De outros
        $other = factory(Medal::class)->create()->toArray();
        // INDEX
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "title", "description", "url"]
            ]
        ]);
        // EDIT
        // recurso do usuário
        $owner['title'] = 'Teste de atualização do título';
        $owner['description'] = 'Teste de atualização de outra descrição';
        $response = $this->actingAs($this->design)
            ->json('put', '/api/medal/' . $owner['id'], $owner);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "title" => $owner["title"],
            "description" => $owner["description"],
        ]]);
        // Recurso de outros
        $other['title'] = 'Teste de atualização do título';
        $other['description'] = 'Teste de atualização de outra descrição';
        $response = $this->actingAs($this->design)
            ->json('put', '/api/medal/' . $other['id'], $other);
        $response->assertStatus(403);
        // DELETE
        // Recurso do usuário
        $response = $this->actingAs($this->design, 'api')
            ->json('delete', '/api/medal/' . $owner['id']);
        $response->assertStatus(204);
        // Recurso de outros
        $response = $this->actingAs($this->design, 'api')
            ->json('delete', '/api/medal/' . $other['id']);
        $response->assertStatus(403);
    }

    /**
     * Testa se um usuário design pode gerenciar recurso de usuário na API.
     *
     * @return void
     */
    public function test_player_can_manage_medal_resource_in_api()
    {
        // Arquivo de imagem falsa com dimensão e tamanho aleatório.
        $image = UploadedFile::fake()->image('image.jpg', rand(100, 800), rand(100, 800))->size(rand(100, 800));
        // CREATE
        $data = factory(Medal::class)->make([
            'image' => $image
        ])->toArray();
        $response = $this->actingAs($this->player, 'api')
            ->json('post', '/api/medal', $data);
        $response->assertStatus(403);

        // Jogador não cria medalha
        // $owner = factory(Game::class)->create(['user_id' => $this->player->id])->toArray();
        // De outros
        $other = factory(Medal::class)->create()->toArray();
        // INDEX
        $response = $this->actingAs($this->player, 'api')->json('get', '/api/medal');
        $response->assertStatus(403);
        // EDIT
        // Recurso de outros
        $other['title'] = 'Teste de atualização do título';
        $other['description'] = 'Teste de atualização de outra descrição';
        $response = $this->actingAs($this->player)
            ->json('put', '/api/medal/' . $other['id'], $other);
        $response->assertStatus(403);
        // DELETE
        // Recurso de outros
        $response = $this->actingAs($this->player, 'api')
            ->json('delete', '/api/medal/' . $other['id']);
        $response->assertStatus(403);
    }
}
