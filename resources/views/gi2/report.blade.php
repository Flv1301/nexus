@php
    use App\Models\Gi2;
@endphp
<div class="card card-primary d-none" id="report">
    <div class="card-header">
        <h3 class="card-title">Gerar Ofício</h3>
    </div>

    @csrf
    <form action="{{route('gi2.oficio')}}" method="post" >
        <div class="card-body">
            @csrf

            <div class="form-group">
                <label for="nomeOperacao">Operação</label>
                <select class="form-control" name="nomeOperacao">
                    @php
                        $operacoes =Gi2::getNameOperations();
                    @endphp
                    @foreach($operacoes as $operacao)
                        <option value="{{ $operacao }}">{{ $operacao }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nomeOperadora">Operadora</label>
                <select class="form-control" name="nomeOperadora">

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
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>

    </form>
</div>

