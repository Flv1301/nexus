{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 11/08/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<table class="table">
    <thead>
    <tr>
        <th>Nome do Documento</th>
        <th>Tipo</th>
        <th>Data Envio</th>
        <th>Analista</th>
        <th>Unidade</th>
        <th>Setor</th>
        <th>Aceito</th>
        <th>Data Analise</th>
        <th>Visualizar</th>
    </tr>
    </thead>
    <tbody>
    @foreach($user->documents as $document)
        <tr>
            <td>{{$document->name}}</td>
            <td>{{$document->type}}</td>
            <td>{{$document->created_at->format('d/m/Y H:i')}}</td>
            <td>{{$document->analyst->name ?? ''}}</td>
            <td>{{$document->unity->name ?? ''}}</td>
            <td>{{$document->sector->name ?? ''}}</td>
            <td>
                <span
                    class="{{ $document->agree ? 'text-success' : 'text-danger' }}">
                    {{ $document->agree ? 'Sim' : 'Não' }}
                </span>
            </td>
            <td>{{$document->updated_at->format('d/m/Y H:i') ?? ''}}</td>
            <td class="text-center">
                <a href="{{route('user.access.contract.view', $document->id)}}" target="_blank">
                    <i class="fas fa-lg fa-eye"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
