<div id="modal1" class="modal modal-login">
    <div class="modal-content">
        <form class="col s8" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="row">
                <div class="col s12">
                    <div class="input-field ">
                        <i class="material-icons prefix">email</i>
                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <label for="email">{{ __('E-Mail Address') }}</label>

                        @error('email')
                        <span id="errorLogin" class="new badge red" data-badge-caption="{{ $message }}" role="alert">
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <div class="input-field ">
                        <i class="material-icons prefix">mode_edit</i>
                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        @if (session('status'))
                            <span id="errorLogin" class="red-text text-darken-2" role="alert">
                                {{ session('status') }}
                            </span>
                        @endif
                        @error('password')
                            <span id="errorLogin" class="badge red" data-badge-caption="{{ $message }}" role="alert">
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <div class="input-field">

                            <label>
                                <input class="filled-in" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>{{ __('Remember Me') }}</span>
                            </label>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <div class="input-field right">
                        <button type="submit" class="waves-effect waves-light btn">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="waves-effect waves-light btn" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('script')
   <script>
    $(document).ready(function(){
        var modalLogin = M.Modal.getInstance($('#modal1'));
        var span = $('#errorLogin').html()
        if(span){
            console.log(span.length)
            modalLogin.open();
        }
    })

    </script>
@show
