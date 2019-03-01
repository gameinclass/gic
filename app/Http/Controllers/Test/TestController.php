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
        $game = Game::findOrFail(5);

        return response()->json($game->medals()->get());
    }
}
