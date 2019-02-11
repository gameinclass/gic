<?php

namespace App\Http\Controllers\Medal;

use App\Models\Medal;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medal\MedalStoreRequest;
use App\Http\Requests\Medal\MedalUpdateRequest;
use App\Http\Resources\Medal\Medal as MedalResource;

class MedalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection;
     */
    public function index()
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', Medal::class);
        return MedalResource::collection(Medal::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MedalStoreRequest $request)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('store', Medal::class);
        $medal = new Medal($request->all());
        // Adiciona o usuário da requisição.
        $medal->user_id = $request->user()->id;
        // Adicionado o caminho do arquivo no disco.
        $medal->path = $request->file('image')->store('medals');
        // Salva o recurso no banco de dados
        if ($medal->save()) {
            return (new MedalResource($medal))
                ->response()
                ->setStatusCode(201);
        }
        return (new MedalResource($medal))
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedalUpdateRequest $request, $id)
    {
        //
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
