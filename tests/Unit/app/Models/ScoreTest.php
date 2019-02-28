<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Score;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ScoreTest extends TestCase
{
    /**
     * Os atributos que são atribuíveis em massa
     *
     * @var array
     */
    protected $fillable = [
        'title', 'value'
    ];

    /**
     * Os atributos excluídos do formulário JSON do modelo.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $score = new Score();
        // Assertions
        $this->assertEquals($this->fillable, $score->getFillable());
        $this->assertEquals($this->hidden, $score->getHidden());
        $this->assertEquals($this->casts, $score->getCasts());
    }

    /**
     * Testa o relacionamento entre ponto e jogos.
     */
    public function test_morph_to_many_games_relation()
    {
        // Model
        $score = new Score();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $score->games());
    }

    /**
     * Testa o relacionamento entre ponto e jogador.
     */
    public function test_morph_to_many_players_relation()
    {
        // Model
        $score = new Score();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $score->players());
    }

    /**
     * Testa o relacionamento entre ponto e grupo.
     */
    public function test_morph_to_many_groups_relation()
    {
        // Model
        $score = new Score();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $score->groups());
    }
}
