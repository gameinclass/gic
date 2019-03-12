<?php

namespace App\Http\Controllers\Game\Medal;

use App\Models\Game;
use App\Models\Medal;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Medal\MedalStoreRequest;
use App\Http\Resources\Medal\Medal as MedalResource;

class MedalController extends Controller
{
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
        $medal = Medal::findOrFail($request->get('id'));

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
     * @param  int $gameId
     * @param  int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($gameId, $medalId)
    {
        $game = Game::findOrFail($gameId);
        // Importante! Procura somente entre as medalhas do jogo ou falha com 404 senão encontrar.
        $medal = $game->medals()->findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        if (Gate::denies('game-medal-destroy', [$game, $medal])) {
            return response()->json([])->setStatusCode(403);
        }
        // 'detach' retorna 0 ou 1 caso o model for desvinculado.
        if ($game->medals()->detach($medal)) {
            return response()->noContent();
        }
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(422);
    }
}
