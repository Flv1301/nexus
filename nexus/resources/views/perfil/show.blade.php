@extends('adminlte::page')
@section('title',"Usuário {$user->nickname}")
@section('plugins.Sweetalert2', true)

@push('css')
<style>
.profile-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 2rem;
    border-radius: 10px 10px 0 0;
    margin-bottom: 0;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid white;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #007bff;
}

.info-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.info-card .card-header {
    background: #f8f9fa;
    border-bottom: 2px solid #007bff;
    font-weight: 600;
    color: #333;
}

.btn-change-password {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    padding: 10px 25px;
    border-radius: 25px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-change-password:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    color: white;
}
</style>
@endpush

<x-page-header title="Meu Perfil"/>
@section('content')
    @include('sweetalert::alert')
    
    <!-- Header do Perfil -->
    <div class="card info-card">
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="col">
                    <h2 class="mb-0">{{$user->name}}</h2>
                    <p class="mb-0 opacity-75">{{$user->nickname}} • {{$user->office}}</p>
                    <small class="opacity-75">{{$user->unity->name}} - {{$user->sector->name}}</small>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-change-password" data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fas fa-key mr-2"></i>Alterar Senha
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Pessoais -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-user mr-2"></i>Informações Pessoais
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-input
                        name="name"
                        label="Nome Completo"
                        disabled
                        value="{{$user->name}}"
                    />
                </div>
                <div class="form-group col-md-4">
                    <x-adminlte-input
                        name="nickname"
                        label="Nome Apresentação"
                        disabled
                        value="{{$user->nickname}}"
                    />
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input
                        name="registration"
                        label="Matricula"
                        disabled
                        value="{{$user->registration}}"
                    />
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input
                        name="cpf"
                        label="CPF"
                        disabled
                        value="{{$user->cpf}}"
                    />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-input
                        name="office"
                        label="Cargo"
                        disabled
                        value="{{$user->office}}"
                    />
                </div>
                <div class="form-group col-md-4">
                    <x-adminlte-input class="mask-date"
                                      name="birth_date"
                                      label="Data Nascimento"
                                      disabled
                                      value="{{$user->birth_date ?? ''}}"
                    />
                </div>
                <div class="col-md-1">
                    <x-adminlte-input
                        name="ddd"
                        label="DDD"
                        class="mask-ddd"
                        disabled
                        value="{{$user->ddd ?? ''}}"
                    />
                </div>
                <div class="form-group col-md-3">
                    <x-adminlte-input
                        name="telephone"
                        label="Telefone"
                        class="mask-phone"
                        disabled
                        value="{{$user->telephone ?? ''}}"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Profissionais -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-building mr-2"></i>Informações Profissionais
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <x-adminlte-input
                        name="sector_id"
                        label="Unidade"
                        disabled
                        value="{{$user->unity->name}}"
                    />
                </div>
                <div class="form-group col-md-5">
                    <x-adminlte-input
                        name="unity_id"
                        label="Setor"
                        disabled
                        value="{{$user->sector->name}}"
                    />
                </div>
                <div class="form-group col-md-1">
                    <x-adminlte-input
                        name="coordinator"
                        label="Coordenador"
                        disabled
                        value="{{$user->coordinator ? 'SIM' : 'NÃO'}}"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Dados de Acesso -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-envelope mr-2"></i>Dados de Acesso
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <x-adminlte-input
                        name="email"
                        label="E-Mail"
                        disabled
                        value="{{$user->email}}"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Endereço -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-map-marker-alt mr-2"></i>Endereço
        </div>
        <div class="card-body">
            @include('address.show')
        </div>
    </div>

    <!-- Modal para Trocar Senha -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('perfil.reset')}}" method="post" id="changePasswordForm">
                    @csrf
                    <div class="modal-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                        <h5 class="modal-title" id="changePasswordModalLabel">
                            <i class="fas fa-key mr-2"></i>Alterar Senha
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            Por segurança, informe sua senha atual e defina uma nova senha com pelo menos 6 caracteres.
                        </div>
                        
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Erro ao alterar senha:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <x-adminlte-input
                                name="password_current"
                                type="password"
                                label="Senha Atual"
                                placeholder="Digite sua senha atual"
                                required
                                value="{{ old('password_current') }}"
                            >
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        
                        <div class="form-group">
                            <x-adminlte-input
                                name="password"
                                type="password"
                                label="Nova Senha"
                                placeholder="Digite a nova senha (mínimo 6 caracteres)"
                                required
                                value="{{ old('password') }}"
                            >
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        
                        <div class="form-group">
                            <x-adminlte-input
                                name="password_confirmation"
                                type="password"
                                label="Confirmação da Nova Senha"
                                placeholder="Digite novamente a nova senha"
                                required
                                value="{{ old('password_confirmation') }}"
                            >
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-2"></i>Salvar Nova Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Abrir modal automaticamente se houver erros de validação de senha
    @if($errors->has('password_current') || $errors->has('password') || $errors->has('password_confirmation'))
        $('#changePasswordModal').modal('show');
    @endif

    // Validação do formulário
    $('#changePasswordForm').on('submit', function(e) {
        var password = $('input[name="password"]').val();
        var confirmation = $('input[name="password_confirmation"]').val();
        
        if (password.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Senha muito curta',
                text: 'A nova senha deve ter pelo menos 6 caracteres.'
            });
            return false;
        }
        
        if (password !== confirmation) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Senhas não conferem',
                text: 'A nova senha e a confirmação devem ser iguais.'
            });
            return false;
        }
    });
});
</script>
@endpush
