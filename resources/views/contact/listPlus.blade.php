{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>DDD</th>
                <th>Telefone</th>
                <th>Proprietário</th>
                <th>Operadora</th>
                <th>Data Ativação</th>
                <th>Data Cancelamento</th>
                <th>IMEI</th>
                <th>IMSI</th>
                <th>Modelo de Aparelho</th>
            </tr>
            </thead>
            <tbody id="tableContacts">
            @if($person->telephones->count())
                @foreach($person->telephones as $telephones)
                    <tr>
                        <td>{{$telephones->ddd}}</td>
                        <td>{{$telephones->telephone}}</td>
                        <td>{{$telephones->owner}}</td>
                        <td>{{$telephones->operator}}</td>
                        <td>{{$telephones->start_link}}</td>
                        <td>{{$telephones->end_link}}</td>
                        <td>{{$telephones->imei}}</td>
                        <td>{{$telephones->imsi}}</td>
                        <td>{{$telephones->device}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" id="contacts" name="contacts[]" value="{{json_encode($telephones)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
@push('js')
    <script>
        function addContacts() {
            const inputs = ['ddd', 'telephone', 'operator', 'owner', 'start_link', 'end_link', 'imei', 'imsi', 'device'];
            const values = inputs.reduce((acc, input) => {
                acc[input] = document.querySelector(`#${input}`).value;
                return acc;
            }, {});

            if (values.telephone === '' && values.imei === '' && values.imsi === '' && values.device === '') {
                return;
            }

            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('id', 'contacts');
            input.setAttribute('name', 'contacts[]');
            input.setAttribute('value', JSON.stringify(values));

            let table = document.querySelector('#tableContacts');
            let tr = document.createElement('tr');
            tr.innerHTML = inputs.map(input => `<td>${values[input]}</td>`).join('') + '<td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>';
            tr.appendChild(input);
            table.appendChild(tr);

            inputs.forEach(input => {
                document.getElementById(input).value = '';
            });
        }
    </script>
@endpush
