<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>E-Mail</th>
            </tr>
            </thead>
            <tbody id="tableEmails">
            @foreach($person->emails as $email)
                <tr>
                    <td>{{$email->email}}</td>
                    <td><i class="fa fa-md fa-fw fa-trash text-danger"
                           onclick="$(this).parent().parent().remove()"
                           title="Remover"></i></td>
                    <input type="hidden" id="emails" name="emails[]" value="{{json_encode($email)}}">
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('js')
    <script>
        function addEmail() {
            const html = `<i class="fa fa-md fa-fw fa-trash text-danger"
                            onclick="$(this).parent().parent().remove()"
                            title="Remover"></i>`;
            let email = document.getElementById('email');
            let emails = [];
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                return;
            }
            emails = {
                email: email.value
            }
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('id', 'emails');
            input.setAttribute('name', 'emails[]');
            input.setAttribute('value', JSON.stringify(emails));
            let table = document.getElementById('tableEmails');
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.append(email.value);
            tr.append(td);
            td = document.createElement('td');
            td.innerHTML = html;
            tr.append(td);
            tr.append(input);
            table.append(tr);
            email.value = '';
        }
    </script>
@endpush
