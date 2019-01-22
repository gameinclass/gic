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
        factory(\App\User::class, rand(2000, 5000))->create()->each(function ($user) {
            $user->actor()->save(factory(\App\Models\Actor::class)->make());
        });
    }
}
