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
</head>
<body>
  <header>
    <nav>
        <div class="navbar-fixed">
            @auth
            <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large black-text"><i class="material-icons">menu</i></a>
            @endauth
            <a class="brand-logo" href="{{ route('home') }}">
                {{-- <img src="{{asset('images/dhl-logo.png')}}" width="20%" height="6,0%"> --}}
                {{-- <img src="{{asset('images/logo.ico')}}" width="185" height="65"> --}}
                {{ config('app.name', 'Laravel') }}
            </a>

            <div id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="left hide-on-med-and-down">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="right hide-on-med-and-down">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link modal-trigger" data-target="modal1" href="#modal1">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                    <!-- Dropdown Structure -->
                    <ul id="dropdown1" class="dropdown-content">
                        {{-- <li><a class="black-text" href="{{ route('myprofile') }}"><img class="circle" alt="profile" width="15%" height="15%" src="{{ auth()->user()->getAvatarUrl() }}"> Mi cuenta</a></li> --}}
                        <li><a class="black-text" href="#"><img class="circle" alt="profile" width="15%" height="15%" src="#"> Mi cuenta</a></li>
                        <li class="divider"></li>
                        <li>
                            <a class="dropdown-item black-text" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                    <!-- Dropdown Trigger -->
                    <li>
                        <a class="dropdown-trigger btn show-on-small" href="#!" data-target="dropdown1">
                            {{-- <i class="material-icons">account_circle</i> --}}
                            {{ Auth::user()->name }}
                            <i class="material-icons right">arrow_drop_down</i>
                        </a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
  </header>
  <ul id="slide-out" class="sidenav">
                <li><div class="user-view">
                        <div class="background">
                          <img src="{{asset('images/background.jpg')}}">
                        </div>
                        <div class="row bottom">
                            <div class="col left">
                                {{-- <a href="{{ route('myprofile') }}"><img class="circle" src="{{ auth()->user()->getAvatarUrl() }}"></a>
                                <a href="{{ route('myprofile') }}"><span class="white-text name">{{Auth::user()->name}}</span></a> --}}
                                <a class="white-text" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            <li class="no-padding">
                <ul class="collapsible">
                        @isAdmin
                        <li>
                          {{-- <a class="waves-effect" href="{{ route('users.index') }}"><i class="tiny material-icons">group</i>Administracion de Usuarios</a> --}}
                            <a class="waves-effect" href="#"><i class="tiny material-icons">group</i>Administracion de Usuarios</a>
                        </li>
                        @endisAdmin
                        <a class=" "></a>
                        <li><div class="divider"></div></li>
                        <li>
                            <a class="waves-effect" href="{{ route('home') }}"><i class="tiny material-icons">dashboard</i>Dashboard</a>
                        </li>
                        <li>
                            <a class="waves-effect" href="{{ route('home') }}"><i class="tiny material-icons">dashboard</i>Cotizaciones</a>
                        </li>
                </ul>
            </li>
            <li><div class="divider"></div></li>
        </ul>
  <div id="app">
    <dashboard-component/>
      {{-- <main class="py-4">
          @yield('content')
      </main> --}}
  </div>
  <footer id="footer" class="page-footer">
      <div class="container">
          <div class="row">
          <div class="col l6 s12">
              <h5 class="black-text">KRANON</h5>
              <i class="material-icons black-text">add_location</i><strong class="black-text"> UBICACIÓN</strong><br>
              <p class="black-text text-lighten-4">
                      Montecito 38 Piso 38 Oficina 32,
                      Colonia Nápoles, CDMX C.P. 03810<br>
                      Privada Solidaridad 1100,
                      Residencial San Jerónimo, 64640 Monterrey.</p>
          </div>
          <div class="col l4 offset-l2 s12">
              <h5 class="black-text">Links</h5>
              <ul>
              <li><a class="black-text text-lighten-3" href="https://twitter.com/P_Kranon" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
              <li><a class="black-text text-lighten-3" href="https://www.facebook.com/PromotoraKranon" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
              </ul>
          </div>
          </div>
      </div>
      <div id="copyright" class="footer-copyright row">
          <div id="bottom" class="container black-text col s12 m6">
                  Derechos reservados © 2016 Kranon S.A. de C.V.
          </div>
          <div class="col s12 m6">
              <a class="black-text text-lighten-4 right" href="https://www.kranon.com/" target="_blank">www.kranon.com</a>
          </div>
      </div>
  </footer>
</body>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> --}}
<script>

</script>
</html>
