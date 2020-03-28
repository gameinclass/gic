<?php

namespace Unscode\Pingo\Seeds;

use Illuminate\Database\Seeder;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Unscode\Pingo\Models\Player::class, 1500)->create();
    }
}
