@extends('adminlte::master')
@section('title','Cadastro de Usuário')
@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop
@section('plugins.Select2', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.TempusDominusBs4', true)
@section('body')
    <div class="container-fluid">
        <div class="flex m-3 items-center">
            <div class="flex items-center">
                @if (config('adminlte.auth_logo.enabled', false))
                    <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}"
                         alt="{{ config('adminlte.auth_logo.img.alt') }}"
                         @if (config('adminlte.auth_logo.img.class', null))
                             class="{{ config('adminlte.auth_logo.img.class') }}"
                         @endif
                         @if (config('adminlte.auth_logo.img.width', null))
                             width="{{ config('adminlte.auth_logo.img.width') }}"
                         @endif
                         @if (config('adminlte.auth_logo.img.height', null))
                             height="{{ config('adminlte.auth_logo.img.height') }}"
                        @endif>
                @else
                    <img src="{{ asset(config('adminlte.logo_img')) }}"
                         alt="{{ config('adminlte.logo_img_alt') }}" height="50">
                @endif
                {{-- Logo Label --}}
                <span class="ml-2">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </div>
            <div class="flex-1 text-center">
                <span class="text-danger font-weight-bold" style="font-size: 19px">O setor responsável irá fazer a validação e liberação em até 3 dias úteis.</span>
            </div>
        </div>
        <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">
            <form action="{{route('user.register.update', $user)}}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body {{ config('adminlte.classes_auth_body', '') }}">
                    @include('auth.form')
                </div>
                <div class="card-footer mt-2">
                    <x-adminlte-button
                        type="submit"
                        label="Cadastrar"
                        theme="success"
                        icon="fas fa-sm fa-save"
                    />
                </div>
            </form>
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif
        </div>
    </div>
@stop
@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
