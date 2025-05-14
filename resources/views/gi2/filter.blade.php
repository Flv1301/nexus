@php
    use App\Models\Gi2;
@endphp
<div class="card card-primary d-none" id="filter">
    <div class="card-header">
        <h3 class="card-title">Filtrar</h3>
    </div>
    <form action="{{route('gi2.intersection')}}" method="post">
        <div class="card-body">
            @php
                $operations =Gi2::getNameOperations();
            @endphp
            @csrf
            <div class="form-group">
                <label for="nomeOperacao">Operação</label>
                <select class="form-control" name="nomeOperacao">

                    @foreach($operations as $operation)
                        <option value="{{ $operation }}">{{ $operation }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nomeOperadora">Operadora</label>
                <select class="form-control" name="nomeOperadora">
                    <option value="" selected>Todas</option>
                    <option value="tim">TIM</option>
                    <option value="claro">Claro</option>
                    <option value="vivo">Vivo</option>
                    <option value="oi">Oi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="natureza">Natureza do Delito</label>
                <select class="form-control" name="natureza">
                    <option value="" selected>Todas</option>
                    <option value="FURTO">Furto</option>
                    <option value="ROUBO">Roubo</option>
                    <option value="EXTRAVIO">Extravio</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>Pesquisar</button>
        </div>
    </form>
</div>
