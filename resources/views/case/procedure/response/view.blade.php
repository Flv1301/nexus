{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<x-adminlte-modal id="modalResponseView"
                  title="Resposta"
                  size='lg'
                  theme="dark">
    <x-loading/>
    <div class="form-row">
        <div class="form-group">
            <label for="response" class="text-dark" id="">
                Resposta
            </label>
        </div>
        <div class="input-group">
            <label id="procedure_response"></label>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label class="d-block">Arquivos</label>
            <table class="table">
                <tbody id="files_response_view">
                </tbody>
            </table>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="primary" label="Fechar" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>
@push('js')
    <script>
        async function modalProcedure(e) {
            let loading = document.getElementById('loading');
            loading.classList.remove('d-none');
            document.querySelector('#procedure_response').innerHTML = '';
            let url = e.dataset.url;
            let response = await fetch(url);
            let json = await response.json();
            document.querySelector('.modal-title')
                .innerHTML = 'Resposta do analista <span class="text-info">' + json.user.nickname + '</span>';
            document.querySelector('#procedure_response').innerHTML = json.response.response;
            let files = document.querySelector('#files_response_view')
            files.innerHTML = '';
            json.files.forEach((e) => {
                let tr = document.createElement('tr');
                let tdname = document.createElement('td');
                let tddate = document.createElement('td');
                let tda = document.createElement('td');
                let a = document.createElement('a');
                a.innerHTML = '<span>Abrir</span>';
                tdname.innerHTML = e.name;
                tddate.innerHTML = e.created_at;
                a.setAttribute('href', "{{url('/caso/analise/arquivo/')}}/" + e.case_file_id);
                tr.append(tdname);
                tr.append(tddate);
                tda.append(a);
                tr.append(tda);
                files.append(tr);
            });
            loading.classList.add('d-none');
        }
    </script>
@endpush
