<table class="table table-responsive col-md-12">
    <thead>
    <tr>
        <th>Rede Social</th>
        <th>Endereço</th>
        <th>ID</th>
        <th>Vínculo</th>
    </tr>
    </thead>
    <tbody id="tableEmails">
    @foreach($person->socials as $social)
        <tr>
            <td>{{ucfirst($social->type)}}</td>
            <td>{{$social->social}}</td>
            <td>{{$social->social_id}}</td>
            <td>{{$social->vinculo}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

