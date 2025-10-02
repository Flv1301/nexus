<div class="card">
    <div class="card-body">
        <form action="{{route("letter.control.store")}}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-input name="recipient" label="Destinatário" class="text-uppercase"/>
                </div>
                <div class="form-group col-md-6"><x-adminlte-input name="subject" label="Assunto" class="text-uppercase"/></div>
            </div>
            <div class="form-row">
                <div class="ml-2">
                    <x-adminlte-button type="submit" icon="fas fa-save" theme="success" label="Numerar"/>
                </div>
            </div>
        </form>
    </div>
</div>
@push('js')
    <script>
        $('form').submit(function(){
            $(this).find(':submit').attr('disabled','disabled');
        });
    </script>
@endpush
