<?php

namespace App\Http\Controllers\Game;

use App\Models\Game;
use App\Models\Phase;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Phase\PhaseStoreRequest;
use App\Http\Requests\Game\Phase\PhaseUpdateRequest;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $gameId
     * @return \Illuminate\Http\Response
     */
    public function index($gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('index', $game);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Game\Phase\PhaseStoreRequest  $request
     * @param  int  $gameId
     * @return \Illuminate\Http\Response
     */
    public function store(PhaseStoreRequest $request, $gameId)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Game\Phase\PhaseUpdateRequest  $request
     * @param  int  $gameId
     * @param  int  $phaseId
     * @return \Illuminate\Http\Response
     */
    public function update(PhaseUpdateRequest $request, $gameId, $phaseId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $gameId
     * @param  int  $phaseId
     * @return \Illuminate\Http\Response
     */
    public function destroy($gameId, $phaseId)
    {
        //
    }
}
