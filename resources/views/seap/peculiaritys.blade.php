{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<table class="table table-striped">
    <thead>
    <tr>
        <th>Tipo</th>
        <th>Orientação</th>
        <th>Descrição</th>
    </tr>
    </thead>
    <tbody>
    @foreach($person->peculiaritys as $peculiarity)
        <tr>
            <td>{{$peculiarity->descricao}}</td>
            <td>{{$peculiarity->orientacao}}</td>
            <td>{{$peculiarity->tipo}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
