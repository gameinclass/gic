<?php

namespace App\Http\Controllers\Player\Game;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::with(['game'])->where('user_id', Auth::user()->id)->get();

        return response()->json($players);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $playerId
     * @param int $gameId
     * @return \Illuminate\Http\Response
     */
    public function show($playerId, $gameId)
    {
        $player = Player::with(['game', 'medals', 'scores'])
            ->findOrFail($playerId);

        $game = Game::with(['medals', 'scores', 'players'])
            ->findOrFail($gameId);

        if (!$game->players->contains($player)) {
            abort(404);
        }

        $ranking = collect();

        foreach ($game->players as $player) {
            $ranking->push([
                'name' => $player,
                'scores_sum' => $player->scores->sum('value')
            ]);
        }

        dd($ranking);

        $data = $game->toArray();
        $data['scores_sum'] = $game->scores->sum('value');

        dd($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
