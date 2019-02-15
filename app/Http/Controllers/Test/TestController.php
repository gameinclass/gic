<?php

namespace App\Http\Controllers\Test;

use App\Models\Game;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //
        $game = Game::with(['medals', 'scores', 'phases', 'players'])->find(1);

        return response()->json($game);
    }
}
