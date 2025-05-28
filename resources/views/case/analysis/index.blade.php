@extends('adminlte::page')
@section('title',"Caso " . $case->name)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.BsCustomFileinput', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.Summernote', true)
@section('plugins.Datatables', true)
@section('plugins.InputMask', true)
<x-page-header title="Movimento {{$case->name}}">
    @if($case->status !== 'CONCLUIDO' && $case->status !== 'ARQUIVADO')
        <div class="mr-2">
            <x-adminlte-button
                data-toggle="modal"
                label="Anexar"
                data-target="#modalCaseAttachment"
                theme="primary"
                icon="fas fa-sm fa-file-import"
            />
        </div>
        <div class="mr-2">
            <x-adminlte-button
                data-toggle="modal"
                label="Tramitar"
                data-target="#modalCaseProcedure"
                theme="success"
                icon="fas fa-sm fa-arrow-right"
            />
        </div>
    @endif
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card card-warning card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tab_data" data-toggle="pill" role="tab" class="nav-link active">Dados</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_persons" data-toggle="pill" role="tab" class="nav-link">Alvos</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_sharing" data-toggle="pill" role="tab" class="nav-link">Compartilhamento</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_files" data-toggle="pill" role="tab" class="nav-link">Arquivos</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_procedures" data-toggle="pill" role="tab" class="nav-link">Tramitação</a>
                </li>
                <li class="d-flex flex-grow-1 align-items-center justify-content-end mr-5">
                    <span>Status:</span>
                    <span class='ml-3 text-sm {{\App\Enums\StatusCaseEnum::from($case->status)->style()}}'>
                        {{$case->status}}
                    </span>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab_data" role="tabpanel">
                    @include('case.analysis.casedata')
                </div>
                <div class="tab-pane" id="tab_persons" role="tabpanel">
                    @include('case.analysis.caseperson')
                </div>
                <div class="tab-pane" id="tab_sharing" role="tabpanel">
                    @include('case.analysis.casesharing')
                </div>
                <div class="tab-pane" id="tab_files" role="tabpanel">
                    @include('case.analysis.casefiles')
                </div>
                <div class="tab-pane" id="tab_procedures" role="tabpanel">
                    @include('case.procedure.list')
                </div>
                <div>
                    @include('case.procedure.create')
                </div>
                <div>
                    @include('case.procedure.view')
                </div>
                <div>
                    @include('case.analysis.caseattachment')
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="form-row d-flex justify-content-between">
                @can('caso.atualizar')
                    @if(($case->user_id == $user->id || ($case->sector_id == $user->sector_id && $user->coordinator)) && $case->status !== 'CONCLUIDO' && $case->status !== 'ARQUIVADO')
                        <div class="form-group">
                            <form action="{{route('case.edit', $case)}}" method="get">
                                <x-adminlte-button
                                    type="submit"
                                    label="Editar"
                                    theme="info"
                                    icon="fas fa-sm fa-edit"
                                />
                            </form>
                        </div>
                    @endif
                @endcan
                @can('caso.excluir')
                    @if($case->user_id == $user->id && $case->status !== 'CONCLUIDO' && $case->status !== 'ARQUIVADO' && !$case->procedures->count())
                        <div class="form-group">
                            <form action="{{route('case.destroy', $case)}}" method="post">
                                @method('DELETE')
                                @csrf
                                <x-adminlte-button
                                    type="submit"
                                    label="Excluir"
                                    theme="danger"
                                    icon="fas fa-sm fa-trash"
                                    class="delete-alert"
                                />
                            </form>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
@endsection
