@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
                <div class="col-12">
                    <div class="mt-5 mb-5">
                        <passport-clients></passport-clients>
                        <passport-authorized-clients></passport-authorized-clients>
                    </div>
                </div>
            <div class="col-12">
                <div class="mt-5 mb-5">
                    <passport-personal-access-tokens></passport-personal-access-tokens>
                </div>
            </div>
        </div>
    </div>
@endsection
