<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    /**
     * Recupera os usuários, ou recupera um usuário específico pelo id.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id = null)
    {
        if (!$id) {
            return \response()->json(User::paginate());
        }
        return \response()->json([
            'data' => User::findOrFail($id)
        ]);
    }
}
