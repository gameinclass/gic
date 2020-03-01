@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 col-md-4 col-lg-4 mb-5">
                <a href="{{route('profile')}}">
                    <div class="card border-white">
                        <div class="card-body pt-5 pb-5">
                            <h2 class="font-weight-bold text-white">Meu perfil</h2>
                        </div>
                    </div>
                </a>
            </div>
            @can('store', \App\Models\Game::class)
                <div class="col-12 col-md-4 col-lg-4 mb-5">
                    <a href="{{route('apps.index')}}">
                        <div class="card border-success">
                            <div class="card-body pt-5 pb-5">
                                <h2 class="font-weight-bold text-success">Meu app(s)</h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
            <div class="col-12"></div>
            @can('update', Auth::user()->actor)
                <div class="col-12 col-md-4 col-lg-4 mb-5">
                    <a href="{{route('user.index')}}">
                        <div class="card border-warning">
                            <div class="card-body pt-5 pb-5">
                                <h2 class="font-weight-bold text-warning">Usuário(s)</h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
        </div>
    </div>
@endsection
