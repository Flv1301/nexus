{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
@php
    use App\Helpers\StateFlagHelper;
@endphp

<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">Antecedentes</h3>
    </div>
    <div class="card-body">
        @if($person->pcpas->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>BO</th>
                    <th>Natureza</th>
                    <th>Data</th>
                    <th>UF</th>
                    <th>Cidade</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->pcpas as $pcpa)
                    <tr>
                        <td>
                            @if($pcpa->uf)
                                {!! StateFlagHelper::getFlagHtml($pcpa->uf, StateFlagHelper::getStateName($pcpa->uf)) !!}&nbsp;
                            @endif
                            {{ $pcpa->bo }}
                        </td>
                        <td>{{ $pcpa->natureza }}</td>
                        <td>{{ $pcpa->data }}</td>
                        <td>{{ $pcpa->uf }}</td>
                        <td>{{ $pcpa->cidade }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum antecedente cadastrado.
            </div>
        @endif
    </div>
</div> 