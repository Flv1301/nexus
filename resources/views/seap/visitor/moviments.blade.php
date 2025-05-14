{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="timeline">
    @foreach($person->moviments->unique() as $move)
        <div>
            <div class="timeline-item">
                <h3 class="timeline-header"><b>Parentesco: </b>{{$move->parentesco_descricao}}</h3>
                <div class="timeline-body">
                    <b>Preso: </b><span class="text-sm">{{$move->stuck->preso_nome}}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
