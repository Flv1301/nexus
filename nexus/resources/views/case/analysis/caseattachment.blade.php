<form action="{{route('case.file.upload', ['id' => $case->id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <x-adminlte-modal id="modalCaseAttachment"
                      title="Anexar arquivo ao Caso."
                      size='lg'
                      theme="dark">
        @include('case.analysis.uploadfile')
        <x-slot name="footerSlot">
            <x-adminlte-button
                type="submit"
                icon="fas fa-sm fa-save"
                class="mr-auto"
                theme="success"
                label=" Anexar"
            />
            <x-adminlte-button theme="danger" label="Cancelar" data-dismiss="modal" icon="fas fa-sm fa-ban"/>
        </x-slot>
    </x-adminlte-modal>
</form>
