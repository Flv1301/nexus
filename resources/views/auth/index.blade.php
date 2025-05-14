{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 08/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Lista de Usuários')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
<x-page-header title="Lista de Usuários">
    <div>
        <a href="{{route('register.create')}}" class="btn btn-success">Cadastrar</a>
    </div>
</x-page-header>
@section('content')
    @include('sweetalert::alert')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'ID',
                    'Nome',
                    'Matricula',
                    'Unidade',
                    'Setor',
                    'Função',
                    'Coordenador',
                    'Status',
                    'Termo Resp.',
                    'Novo Cad.',
                    ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[0, 'asc']],
                    'columns' => [null, null, null, null, null, null, null, null, null, null, ['orderable' => false]],
                    'language' => [
                        'paginate' => [
                            'first' => 'Primeiro',
                            'last' => 'Último',
                            'next' => 'Próximo',
                            'previous' => 'Anterior',
                        ],
                        'search' => 'Pesquisar na Tabela',
                        'lengthMenu'=>    "Mostrar  _MENU_  Resultados",
                        'info'=>           "Mostrando _START_ a _END_ de _TOTAL_ Resultados.",
                        'infoEmpty'=>      "Mostrando 0 Resultados.",
                        'infoFiltered'=>   "(Filtro de _MAX_ Resultados no total)",
                        'loadingRecords'=> "Pesquisando...",
                        'zeroRecords'=>    "Nem um dado(s) encontrado(s)",
                        'emptyTable'=>     "Sem dados!",
                    ],
                ];
            @endphp
            <x-adminlte-datatable id="tbl_users" :heads="$heads" :config="$config" striped hoverable>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->nickname}}</td>
                        <td>{{$user->registration}}</td>
                        <td>{{$user->unity->name}}</td>
                        <td>{{$user->sector->name}}</td>
                        <td>{{$user->role}}</td>
                        <td>{!! $user->coordinator ? '<span class="badge badge-info">SIM</span>'
                             : '<span class="badge badge-secondary">Não</span>' !!}
                        </td>
                        <td>{!! $user->status ? '<span class="badge badge-success">Ativo</span>'
                             : '<span class="badge badge-danger">Inativo</span>' !!}
                        </td>
                        <td>{!! $user->documents->where('agree', false)->count() ? '<span class="badge badge-danger">Pendente</span>'
                             : ($user->documents->where('agree', true)->count() ? '<span class="badge badge-success">Ok</span>' : '') !!}
                        </td>
                        <td>{!! $user->code_controller == 'CADASTRADO' ? '<span class="badge badge-success">SIM</span>'
                             : '<span class="badge badge-danger">NAO</span>' !!}
                        </td>
                        <td>
                            <div class="d-flex">
                                @can('usuario.ler')
                                    <a href="{{route('register.show', $user)}}" class='btn btn-sm mx-1' title='Detalhe'>
                                        <i class='fa fa-lg fa-fw fa-eye'></i>
                                    </a>
                                @endcan
                                @can('usuario.atualizar')
                                    <a href="{{route('register.edit', ['id' => $user->id])}}"
                                       class='btn btn-sm mx-1 text-primary' title='Edição'>
                                        <i class='fa fa-lg fa-fw fa-edit'></i>
                                    </a>
                                @endcan
                                @can('usuario.deletar')
                                    <form class="d-inline"
                                          action="{{route('register.destroy', ['id' => $user->id])}}"
                                          method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm text-danger mx-1 delete-alert" title="Deletar">
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endsection
