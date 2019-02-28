<?php

namespace App\Http\Controllers\Game\Phase;

use App\Models\Game;
use App\Models\Phase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Phase\PhaseStoreRequest;
use App\Http\Requests\Game\Phase\PhaseUpdateRequest;
use App\Http\Resources\Game\Phase\Phase as PhaseResource;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int $gameId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('index', [Phase::class, $game]);
        /* if (Gate::denies('game-phase-index', $game)) {
             return response()->json([])->setStatusCode(403);
         }*/
        // Retorna o uma coleção de recursos.
        return PhaseResource::collection(Phase::where('game_id', $game->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Game\Phase\PhaseStoreRequest $request
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PhaseStoreRequest $request, $gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('store', [Phase::class, $game]);

        /*if (Gate::denies('game-phase-store', $game)) {
            return (new PhaseResource($phase))
                ->response()
                ->setStatusCode(403);
        }*/

        $phase = new Phase($request->all());
        // Salva o recurso no banco de dados
        if ($game->phases()->save($phase)) {
            return (new PhaseResource($phase))
                ->response()
                ->setStatusCode(201);
        }
        return (new PhaseResource($phase))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Game\Phase\PhaseUpdateRequest $request
     * @param  int $gameId
     * @param  int $phaseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PhaseUpdateRequest $request, $gameId, $phaseId)
    {
        $game = Game::findOrFail($gameId);
        $phase = Phase::findOrFail($phaseId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $game, $phase);

        // Atualiza o recurso no banco de dados
        $phase->update($request->all());
        return (new PhaseResource($phase))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $gameId
     * @param  int $phaseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($gameId, $phaseId)
    {
        $game = Game::findOrFail($gameId);
        $phase = Phase::findOrFail($phaseId);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $game, $phase);

        // Remove o recurso do banco de dados
        if ($phase->delete()) {
            return response()->noContent();
        }
        return (new PhaseResource($phase))
            ->response()
            ->setStatusCode(422);
    }
}
