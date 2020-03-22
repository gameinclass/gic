<?php

namespace Unscode\Pingo\Http\Controllers\Player;

use Unscode\Pingo\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $db = Player::with(['game', 'scores'])
            ->withCount(['medals', 'scores'])
            ->where('user_id', Auth::user()->id)
            ->get();
        $players = $db->toArray();
        foreach ($players as &$player) {
            $player['accumulated_scores'] = 0;
            foreach ($player['scores'] as &$score) {
                $player['accumulated_scores'] = $player['accumulated_scores'] + $score['value'];
            }
            unset($player['scores']);
            unset($player); // break the reference with the last element
        }
        unset($score); // break the reference with the last element
        $players['games_count'] = count($players);
        $players['scores_sum'] = 0;
        $players['medals_sum'] = 0;
        foreach ($players as $player) {
            $players['medals_sum'] = $players['medals_sum'] + $player['medals_count'];
            $players['scores_sum'] = $players['scores_sum'] + $player['accumulated_scores'];
        }
        return response()->json(['data' => $players]);
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
     * @return \Illuminate\Http\Response
     */
    public function show($playerId)
    {
        $player = Player::with(['game', 'medals', 'scores', 'game.medals', 'game.scores'])
            ->where('user_id', Auth::user()->id)
            ->first();

        dd($player->toArray());
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
