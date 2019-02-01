<?php
namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Score;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ScoreTest extends TestCase
{
    protected $fillable = [
        'title', 'value'
    ];

    protected $hidden = [];

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
