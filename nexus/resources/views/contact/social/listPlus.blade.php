<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Rede Social</th>
                <th>Endereço/Perfil</th>
                <th>ID</th>
                <th>Vínculo</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody id="tableSocials">
            @foreach($person->socials as $social)
                <tr>
                    <td>{{$social->type}}</td>
                    <td>{{$social->social}}</td>
                    <td>{{$social->social_id}}</td>
                    <td>{{$social->vinculo}}</td>
                    <td><i class="fa fa-md fa-fw fa-trash text-danger"
                           onclick="$(this).parent().parent().remove()"
                           title="Remover"></i></td>
                    <input type="hidden" id="socials_{{ $loop->index }}" name="socials[]" value="{{json_encode($social)}}">
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('js')
    <script>
        function addSocial() {
            const html = `<i class="fa fa-md fa-fw fa-trash text-danger"
                            onclick="$(this).parent().parent().remove()"
                            title="Remover"></i>`;
            let social = document.getElementById('social');
            let socialId = document.getElementById('social_id');
            let type = document.getElementById('type');
            let vinculo = document.getElementById('vinculo');
            let socials = [];
            if (social.value === '') {
                return;
            }
            socials = {
                social: social.value,
                social_id: socialId.value,
                type: type.value,
                vinculo: vinculo.value,
            }
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('id', 'socials_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9));
            input.setAttribute('name', 'socials[]');
            input.setAttribute('value', JSON.stringify(socials));
            let table = document.getElementById('tableSocials');
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.append(type.value);
            tr.append(td);
            td = document.createElement('td');
            td.append(social.value);
            tr.append(td);
            td = document.createElement('td');
            td.append(socialId.value);
            tr.append(td);
            td = document.createElement('td');
            td.append(vinculo.value);
            tr.append(td);
            td = document.createElement('td');
            td.innerHTML = html;
            tr.append(td);
            tr.append(input);
            table.append(tr);
            social.value = '';
            socialId.value = '';
            type.value = '';
            vinculo.value = '';
        }
    </script>
@endpush
