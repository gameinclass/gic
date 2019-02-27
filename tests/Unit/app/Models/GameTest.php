<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class GameTest extends TestCase
{
    /**
     * Os atributos que são atribuíveis em massa
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description'
    ];

    /**
     * Os atributos excluídos do formulário JSON do modelo.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        "id" => "int",
        'user_id' => 'integer',
    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertEquals($this->fillable, $game->getFillable());
        $this->assertEquals($this->hidden, $game->getHidden());
        $this->assertEquals($this->casts, $game->getCasts());
    }

    /**
     * Testa o relacionamento entre jogo e usuário.
     *
     * @return void
     */
    public function test_belongs_to_user_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $game->user());
    }

    /**
     * Testa o relacionamento entre jogo e medalha.
     *
     * @return void
     */
    public function test_morph_to_many_medals_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $game->medals());
    }

    /**
     * Testa o relacionamento entre jogo e ponto.
     *
     * @return void
     */
    public function test_morph_to_many_scores_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $game->scores());
    }

    /**
     * Testa o relacionamento entre jogo e fase.
     *
     * @return void
     */
    public function test_has_many_phases_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(HasMany::class, $game->phases());
    }

    /**
     * Testa o relacionamento entre jogo e jogadores.
     *
     * @return void
     */
    public function test_has_many_players_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(HasMany::class, $game->players());
    }
}
