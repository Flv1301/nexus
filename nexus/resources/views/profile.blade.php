@extends('adminlte::page')

@section('title', 'Perfil do Usuário')

@section('content_header')
    <h1>Perfil do Usuário</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4>Dados do Usuário</h4>
            <ul>
                <li><strong>Nome:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
            </ul>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h4>Trocar Senha</h4>
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                <div class="form-group">
                    <label for="current_password">Senha Atual</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirme a Nova Senha</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Alterar Senha</button>
            </form>
        </div>
    </div>
@endsection 