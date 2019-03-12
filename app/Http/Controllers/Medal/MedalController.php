<?php

namespace App\Http\Controllers\Medal;

use App\Models\Medal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Medal\MedalCollection;
use App\Http\Requests\Medal\MedalStoreRequest;
use App\Http\Requests\Medal\MedalUpdateRequest;
use App\Http\Resources\Medal\Medal as MedalResource;

class MedalController extends Controller
{
    /**
     * Exibe uma liste de recurso de acordo com a pesquisa
     *
     * @param  string $search
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection;
     */
    public function search($search)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', Medal::class);
        // Os valores retornados da pesquisa
        return MedalResource::collection(Medal::where('title', 'like', "%{$search}%")->take(3)->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Medal\MedalCollection;
     */
    public function index()
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', Medal::class);
        return new MedalCollection(Medal::orderBy('created_at', 'desc')
            ->paginate());
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
        $medal->path = $request->file('image')->store('medals', 'public');
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
     * @param  int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($medalId)
    {
        $medal = Medal::findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $medal);
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MedalUpdateRequest $request, $medalId)
    {
        $medal = Medal::findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $medal);
        // Adiciona o usuário da requisição.
        $medal->user_id = $request->user()->id;
        // Atualiza o recurso no banco de dados
        $medal->update($request->all());
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($medalId)
    {
        $medal = Medal::findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $medal);
        // Remove o recurso do banco de dados
        if ($medal->delete()) {
            // Remove o arquivo da medalha do storage
            Storage::disk('public')->delete($medal->path);
            return response()->noContent();
        }
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(422);
    }
}
