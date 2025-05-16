<table class="table table-responsive col-md-12">
    <thead>
    <tr>
        <th>Rede Social</th>
        <th>Endereço</th>
    </tr>
    </thead>
    <tbody id="tableEmails">
    @foreach($person->socials as $social)
        <tr>
            <td>{{ucfirst($social->type)}}</td>
            <td>{{$social->social}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

