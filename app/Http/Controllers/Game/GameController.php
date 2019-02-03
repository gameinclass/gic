<?php

namespace App\Http\Controllers\Game;

use App\Models\Game;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameStoreRequest;
use App\Http\Requests\Game\GameUpdateRequest;
use App\Http\Resources\Game\Game as GameResource;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return GameResource::collection(Game::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GameStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GameStoreRequest $request)
    {
        // Game
        $game = new Game($request->all());
        // Adiciona o usuário da requisição.
        $game->user_id = $request->user()->id;
        // Armazena os recursos
        if ($game->save()) {
            return (new GameResource($game))
                ->response()
                ->setStatusCode(201);
        }
        return (new GameResource($game))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GameUpdateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GameUpdateRequest $request, $id)
    {
        // Game
        $game = Game::findOrFail($id);
        // Adiciona o usuário da requisição.
        $game->user_id = $request->user()->id;
        // Atualiza os recursos
        $game->update($request->all());
        return (new GameResource($game))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Game
        $game = Game::findOrFail($id);
        if ($game->delete()) {
            return response()->noContent();
        }
        return response('', 422);
    }
}