@section('title', 'Login')

@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@push('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
@section('plugins.Sweetalert2', true)
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('adminlte::adminlte.login_message'))

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        /* Página: fundo suave e mais quente */
        body.login-page {
            background: linear-gradient(135deg, #f7fbff 0%, #eaf5ff 100%);
            min-height: 100vh;
        }
        /* Ajustes do card de login: largura, borda primária e sombra */
        .login-box .card {
            max-width: 420px;
            margin: 2.5rem auto;
            border-radius: .85rem;
            box-shadow: 0 10px 30px rgba(20, 40, 80, 0.12);
            border-top: 3px solid rgba(0,123,255,0.9);
        }
        /* Espaçamento interno maior para respirar */
        .login-box .card-body {
            padding: 1.75rem;
        }
        /* Estados de foco mais visíveis */
        .login-box .form-control:focus {
            border-color: #66afe9;
            box-shadow: 0 0 0 .12rem rgba(102,175,233,0.25);
        }
        /* Botão mais chamativo */
        .auth-btn { font-weight: 600; }
        /* Aumenta a área clicável do label "Lembrar-me" e mantém cursor */
        .remember-label { cursor: pointer; padding-left: .35rem; display: inline-block; }
        /* Toggle de senha dentro do input-group-text para alinhar corretamente */
        .password-toggle { cursor: pointer; padding: .375rem .65rem; color: #6c757d; }
        .password-toggle:focus { outline: none; box-shadow: none; }
        .login-spinner { margin-left: .5rem; }
    </style>
@stop

@section('auth_body')
    <x-page-messages/>
    {{-- Mensagem genérica para erros de autenticação --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Credenciais inválidas. Verifique seus dados e tente novamente.
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('info') }}
        </div>
    @endif
    <form action="{{ $login_url }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="form-group" id="email_login">
            <label for="email_input" class="form-label">{{ __('adminlte::adminlte.email') }}</label>
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>    

        {{-- Password field --}}
        <div class="form-group" id="senha_login">
            <label for="password_input" class="form-label">{{ __('adminlte::adminlte.password') }}</label>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="{{ __('adminlte::adminlte.password') }}">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Login field --}}
        <div class="row" id="linha_botao">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>
{{--            <div class="mb-2">--}}
{{--                @if(env('APP_ENV') === 'production')--}}
{{--                    {!! NoCaptcha::renderJs() !!}--}}
{{--                    {!! NoCaptcha::display() !!}--}}
{{--                @endif--}}
{{--            </div>--}}
            <div class="col-5">
                <button type=submit
                        class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
        <input type="hidden" name="latitude" id="latitude"/>
        <input type="hidden" name="longitude" id="longitude"/>
        <input type="hidden" name="ip_public" id="ip_public"/>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($register_url)
        <p class="my-0 text-center">
            Não tem cadastro?
            <a href="{{ $register_url }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
@push('js')
    <script>
        $(document).ready(function () {
            // Localização opcional - não bloquear o login se não permitida
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    $('#latitude').val(latitude);
                    $('#longitude').val(longitude);
                }, function (error) {
                    // Localização negada ou erro - continuar sem bloquear o login
                    console.log('Localização não disponível ou negada pelo usuário');
                })
            }

            // Lógica para ajustar a altura da caixa de login
            var loginBox = $('.login-box');
            var card = loginBox.find('.card');

            var errorMessages = card.find('.invalid-feedback, .alert-info');
            var extraHeight = 0;

            if (errorMessages.length > 0) {
                errorMessages.each(function () {
                    extraHeight += $(this).outerHeight(true);
                });
            }

            var currentHeight = card.outerHeight();

            if (extraHeight > 0) {
                card.css('min-height', (currentHeight + extraHeight) + 'px');
            }
        });
    </script>
@endpush

