@section('title', 'Cadastro de Usuário')

@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('plugins.Sweetalert2', true)
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop
@section('auth_body')
    <x-page-messages/>
    <form action="{{route('user.register.verify')}}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="registration" class="form-control @error('registration') is-invalid @enderror"
                   value="{{ old('registration') }}" placeholder="Matricula" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>

            @error('registration')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        @if(session()->has('code'))
            <div class="input-group mb-1">
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                       value="{{ old('code') }}" placeholder="Código de verificação" autofocus>

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-key"></span>
                    </div>
                </div>
                @error('code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-3">
                <a href="">Re-enviar código</a>
            </div>
        @endif
        {{-- Login field --}}
        <div class="row  d-flex justify-content-center">
            <div class="mb-2">
                @if(env('APP_ENV') === 'production')
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                @endif
            </div>
            <div class="col-5">
                <button type=submit
                        class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-search"></span>
                    Verificar
                </button>
            </div>
        </div>
    </form>
@stop
