<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(GamesTableSeeder::class);
        $this->call(MedalsTableSeeder::class);
        $this->call(ScoresTableSeeder::class);
        $this->call(PhasesTableSeeder::class);
        $this->call(PlayersTableSeeder::class);
    }
}
