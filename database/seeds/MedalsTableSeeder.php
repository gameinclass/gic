<?php

use Illuminate\Database\Seeder;

class MedalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $d = Storage::disk('public')->deleteDirectory('medals');
        factory(\App\Models\Medal::class, 50)->create();
    }
}
