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
                <form action="{{route('permission.store')}}" method="post">
                    @csrf
                    <div class="form-row">
                        @include('permission.form')
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

