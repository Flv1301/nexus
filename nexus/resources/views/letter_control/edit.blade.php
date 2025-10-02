<div class="card">
    <div class="card-body">
        <form action="{{route("letter.control.update", $letterControl)}}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-input name="recipient" label="Destinatário" value="{{$letterControl->recipient}}"/>
                </div>
                <div class="form-group col-md-6">
                    <x-adminlte-input name="subject" label="Assunto" value="{{$letterControl->subject}}"/>
                </div>
                <div class="form-group col-md-2">
                    <div class="input-group">
                        <x-adminlte-input name="number_of" label="Número" disabled value="{{$letterControl->number}}"/>
                        <input type="hidden" value="{{$letterControl->id}}" name="id">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="ml-2">
                    <x-adminlte-button type="submit" icon="fas fa-save" theme="success" label="Atualizar"/>
                </div>
            </div>
        </form>
    </div>
</div>
