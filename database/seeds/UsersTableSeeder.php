<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 30)->create()->each(function ($user) {
            $user->actor()->save(factory(\App\Models\Actor::class)->make([
                // Somente jogadores
                'is_administrator' => false,
                'is_design' => false,
                'is_player' => true,
            ]));
        });
    }
}
