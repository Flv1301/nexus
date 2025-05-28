<div class="card card-warning card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <div class="d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tab_hydra" data-toggle="pill" aria-controls="tab_data" role="tab"
                       class="nav-link active">NEXUS</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_cortex" data-toggle="pill" aria-controls="tab_address" role="tab"
                       class="nav-link">CORTEX</a>
                </li>
            </ul>
            <div class="mr-3">
                <a href="{{route('person.search.report', ['id' => $person->id])}}" title="Gerar Relatório PDF" target="_blank">
                    <i class="fas fa-lg fa-file-pdf text-danger"></i>
                </a>
            </div>
        </div>
    </div>
