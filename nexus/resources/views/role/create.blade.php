{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <form action="{{route('role.store')}}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <x-adminlte-input
                                name="name"
                                label="Função"
                                placeholder="Digite o nome da função."
                                value="{{old('name')}}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text text-black">
                                        <i class="fas fa-keyboard"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="form-group col-md-6">
                            @include('permission.list')
                        </div>
                        <div class="form-group d-flex align-items-center mt-3">
                            <x-adminlte-button
                                type="submit"
                                label="Cadastrar"
                                theme="success"
                                icon="fas fa-sm fa-save"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

