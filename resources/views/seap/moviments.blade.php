{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="timeline">
    @foreach($person->moviments->sortByDesc('movimentacao_data') as $move)
        <div>
            <i class="fas fa-arrow-down bg-blue"></i>
            <div class="timeline-item">
                <span class="time text-info text-md"><i class="fas fa-clock"></i> {{$move->movimentacao_data}}</span>
                <h3 class="timeline-header">{{$move->tipomovimentacao_descricao}}</h3>
                <div class="timeline-body">
                    <span class="text-sm">
                    {{$move->movimentacaoobservacao_descricao}}
                    </span>
                </div>

            </div>
        </div>
    @endforeach
</div>
