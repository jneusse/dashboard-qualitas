@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row center-align">
        <div class="col s8">
            <div class="card">
                <div class="card-content">{{ __('Dashboard') }}</div>

                <div class="card-action">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Bienvenido') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
