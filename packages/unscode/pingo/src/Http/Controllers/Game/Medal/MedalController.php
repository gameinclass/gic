<?php

namespace Unscode\Pingo\Http\Controllers\Game\Medal;

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
        $game = Game::findOrFail($gameId); // 404 se não encontrado!
        // Verifica se a ação é autorizada ...
        if (Gate::denies('game-medal-store', $game)) {
            return response()->json([
                'data' => $request->all(),
                'message' => 'Esta ação não é autorizada.'
            ])->setStatusCode(403);
        }
        // Procura peda medalha no banco de dados
        $medal = Medal::find($request->get('id'));
        if (!$medal) {
            return response()->json([
                'data' => $request->all(),
                'message' => 'Não foi possível criar o recurso'
            ])->setStatusCode(400);
        }
        // Para evitar duplicidade, verifica se a medalha já foi adicionada.
        if ($game->medals->contains($medal)) {
            return (new MedalResource($medal))
                ->additional(['errors' => 'A medalha já foi adicionada.'])
                ->response()
                ->setStatusCode(422);
        }
        // Faz a associação da medalha com o jogo.
        $game->medals()->attach($medal); // retorna void
        // Verifica se a medalha foi adicionada
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
