@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(session()->has('updated_successful'))
                    <p class="mb-5">{{session()->get('updated_successful')}}</p>
                @endif
                @if(session()->has('updated_unsuccessful'))
                    <p class="mb-5">{{session()->get('updated_unsuccessful')}}</p>
                @endif
            </div>
            <div class="col-12">
                <form>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="q" placeholder="Pesquisar por nome"
                               aria-label="Pesquisa por nome" aria-describedby="button-addon">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                @forelse ($users as $user)
                    <div class="row border rounded bg-white pt-2 pr-5 pb-2 pl-5 m-0 mb-3">
                        <div class="col-12 col-lg-6 col-xl-6">
                            {{$user->name}}
                            <p class="font-italic">{{$user->email}}</p>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-6">
                            <form method="post" action="{{route('user.actor.update', [$user, $user->actor])}}">
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-6 col-xl-6">
                                        <!-- Administrator -->
                                        <div class="custom-control custom-checkbox mr-sm-2">
                                            <input name="actor" type="radio" class="custom-control-input"
                                                   value="is_administrator"
                                                   id="{{md5($user->name . 'administrator')}}" {{$user->actor->is_administrator ? 'checked' : ''}}>
                                            <label class="custom-control-label"
                                                   for="{{md5($user->name . 'administrator')}}">
                                                Administrador
                                            </label>
                                        </div>
                                        <!-- Design -->
                                        <div class="custom-control custom-checkbox mr-sm-2">
                                            <input name="actor" type="radio" class="custom-control-input"
                                                   value="is_design"
                                                   id="{{md5($user->name . 'design')}}" {{$user->actor->is_design ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="{{md5($user->name . 'design')}}">
                                                Design
                                            </label>
                                        </div>
                                        <!-- Player -->
                                        <div class="custom-control custom-checkbox mr-sm-2">
                                            <input name="actor" type="radio" class="custom-control-input"
                                                   value="is_player"
                                                   id="{{md5($user->name . 'player')}}" {{$user->actor->is_player ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="{{md5($user->name . 'player')}}">
                                                Player
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 col-xl-6">
                                        <!-- Action -->
                                        <button type="submit" class="btn btn-sm btn-warning">Atualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Lista vazia ...</p>
                @endforelse
            </div>
            <div class="col-12">
                @if(request('q'))
                    {{ $users->appends(['q' => request('q')])->links() }}
                @else
                    {{ $users->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
