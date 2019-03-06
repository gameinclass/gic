<?php

namespace App\Http\Controllers\Game;

use App\Models\Game;
use App\Models\Medal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Medal\MedalStoreRequest;
use App\Http\Resources\Medal\Medal as MedalResource;

class MedalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int $gameId
     * @return \Illuminate\Http\Response
     */
    public function index($gamrId)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Game\Medal\MedalStoreRequest
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MedalStoreRequest $request, $gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        if (Gate::denies('game-medal-store', $game)) {
            return response()->json($request->all())->setStatusCode(403);
        }
        // Procura peda medalha no banco de dados
        $medal = Medal::findOrFail($request->get('medal_id'));
        // Faz a associação da medalha com o jogo.
        $game->medals()->attach($medal);
        // Verifica se a medalha foi adicionado, pois o código acima retorna void
        if ($game->medals()->find($medal->id)) {
            return (new MedalResource($medal))
                ->response()
                ->setStatusCode(201);
        }
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
