<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Actor;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        // User
        $user = new User($request->all());
        // Adiciona um senha aleatória de 6 dígitos para o usuário.
        $user->password = str_random(6);
        // Actor
        $actor = new Actor($request->input('actor'));
        // Armazena o recurso
        if ($user->save() && $user->actor()->save($actor)) {
            return (new UserResource($user))
                ->response()
                ->setStatusCode(201);
        }
        return (new UserResource($user))
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
    public function update(UserUpdateRequest $request, $id)
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
