<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Regras de validação para os recursos
     *
     * @var array
     */
    private $rules = [
        'actor.is_administrator' => 'required|boolean',
        'actor.is_design' => 'required|boolean',
        'actor.is_player' => 'required|boolean',
        'name' => 'required|min:1|max:255',
        'email' => 'required|email|unique:users'
    ];

    /**
     * Recupera os usuários, ou recupera um usuário específico pelo id.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show($id = null)
    {
        if (!$id) {
            return \response()->json(User::with('actor')->paginate());
        }
        return \response()->json([
            'data' => User::with('actor')->findOrFail($id)
        ]);
    }

    /**
     * Armazena o usuário no banco de dados
     *
     * @param Request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return \response()->json(['errors' => $validator->errors(), 'data' => $request->all()], 422);
        }
        // User
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->email_verified_at = $request->input('email_verified_at');
        $user->password = app('hash')->make('secret');
        // Actor
        $actor = new Actor();
        $actor->is_administrator = $request->input('actor.is_administrator');
        $actor->is_design = $request->input('actor.is_design');
        $actor->is_player = $request->input('actor.is_player');
        // Save
        if ($user->save()) {
            if ($user->actor()->save($actor)) {
                $data['status'] = 'success';
                $data['data'] = $user->toArray();
                $data['data']['actor'] = $actor->toArray();
                // Response
                return \response()->json($data, 201);
            }
            $user->delete();
            // Response
            return \response()->json(['data' => $request->all()], 422);
        }
        // Response
        return \response()->json(['data' => $request->all()], 422);
    }
}
