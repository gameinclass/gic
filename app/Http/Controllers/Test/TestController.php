<?php

namespace App\Http\Controllers\Test;

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
        $game = Game::orderBy('created_at', 'desc')
            ->paginate();

        return response()->json($game);
    }
}
