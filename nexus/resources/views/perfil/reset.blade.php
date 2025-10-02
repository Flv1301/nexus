{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/03/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title', 'Perfil - Alteração de senha')
<x-page-header title="Alteração de Senha"/>
@section('content')
    <form action="{{route('perfil.reset')}}" method="post">
        @csrf
        <x-page-messages/>
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <x-adminlte-input
                                    name="password_current"
                                    type="password"
                                    label="Senha Atual"
                                    value="{{old('password_current')}}"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <x-adminlte-input
                                    name="password"
                                    type="password"
                                    label="Nova Senha"
                                    value="{{old('password')}}"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <x-adminlte-input
                                    name="password_confirmation"
                                    type="password"
                                    label="Confirmação de Senha"
                                    value="{{old('password_confirmation')}}"
                                />
                            </div>
                        </div>
                        <div class="card-footer">
                            <x-adminlte-button
                                type="submit"
                                label="Atualizar"
                                theme="success"
                                icon="fas fa-sm fa-save"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
