@if($result->situacaopreso_descricao == 'EM MONITORACAO')
    @can('seap_monitoramento')
        <a href="{{ route('seap.maps.monitor.stuck', ['id' => $result->id_preso]) }}"
           target="_blank"
           class="open-link-monitor ml-1"><i class="fas fa-lg fa-satellite" style="color: #218838"></i></a>
    @endcan
@endif
