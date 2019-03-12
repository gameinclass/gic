<?php

namespace App\Http\Controllers\Test;

use App\Models\Phase;
use App\Models\User;
use App\Models\Medal;
use App\Models\Game;
use App\Models\Player;
use App\Models\Score;
use Faker\Factory;
use Faker\Generator as Faker;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $search = "@example.net";
        $users = User::with('actor')->whereHas('actor', function ($query) {
            $query->where('is_player', true);
        })->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->take(10)->get();

        $phase = \factory(Phase::class)->make();
        dd($phase->toArray());
    }
}
