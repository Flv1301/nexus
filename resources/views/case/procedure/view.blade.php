<x-adminlte-modal id="modalProcedureView"
                  title="Tramitação"
                  size='lg'
                  theme="dark">
    <x-loading/>
    <div class="form-row">
        <div class="form-group">
            <label for="request" class="text-dark">
                Solicitação
            </label>
        </div>
        <div class="input-group">
            <label id="request_procedure"></label>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label class="d-block">Arquivos</label>
            <table class="table">
                <tbody id="files_procedure_view">
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
            document.querySelector('#request_procedure').innerHTML = '';
            let url = e.dataset.url;
            let response = await fetch(url);
            let json = await response.json();
            document.querySelector('#request_procedure').innerHTML = json.procedure.request;
            const files = document.querySelector('#files_procedure_view')
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
