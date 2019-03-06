<?php

namespace App\Http\Controllers\Test;

use App\Models\Medal;
use App\Models\Game;
use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Models\Score;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $game = Game::find(1);
        dd($game->medals()->detach([2525]));

        return response()->json($game->medals());
    }
}
