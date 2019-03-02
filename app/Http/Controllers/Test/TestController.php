<?php

namespace App\Http\Controllers\Test;

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
        $player = Player::findOrFail(2);

        return response()->json($player->scores()->get());
    }
}
