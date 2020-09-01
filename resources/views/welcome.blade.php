<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/material-design-icons/iconfont/material-icons.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style media="screen">
        .modal-login {
          display: none;
          position: fixed;
          left: 0;
          right: 0;
          background-color: #fafafa;
          padding: 0;
          max-height: 700px;
          width: 380px;
          margin: auto;
          overflow-y: auto;
          border-radius: 2px;
          will-change: top, opacity;
        }
    </style>
</head>
    <body>
        <nav>
          <div class="nav-wrapeper">
            <a href="#" class="brand-logo">{{ config('app.name', 'Laravel') }}</a>
            @if (Route::has('login'))
              <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a class="nav-link modal-trigger" data-target="modal1" href="#modal1">{{ __('Login') }}</a></li>
                @if (Route::has('register'))
                  <li><a href="{{ route('register') }}">Register</a></li>
                @endif
              </ul>
            @endif
          </div>
        </nav>

            <div class="content">
                <div class="title m-b-md" style="margin-top:10%">
                    {{-- {{ config('app.name', 'Laravel') }} --}}
                    <img style="width: 100%; height:48%;" src="{{ asset('images/welcome.png') }}" alt="" >
                </div>
            </div>

        @include('auth.login')
        <div id="app">

        </div>

    </body>
</html>
