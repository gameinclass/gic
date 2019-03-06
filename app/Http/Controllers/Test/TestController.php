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
        $medal = Game::find(1)->medals()->find(24);

        return response()->json($medal->pivot->medallable_id);
    }
}
