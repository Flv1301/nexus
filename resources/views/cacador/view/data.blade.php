<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="form-group col-md-8">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="name" class="form-label">Nome completo</label>
                        <p id="name" style="text-transform:uppercase">{{$person->nome}}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="birth_date" class="form-label">Data Nascimento</label>
                        <p id="birth_date">{{$person->nascimento}}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <p id="cpf">{{$person->cpf}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="mother" class="form-label">Nome da mãe</label>
                        <p id="mother">{{$person->mae}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="father" class="form-label">Nome do pai</label>
                        <p id="father">{{$person->pai}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nacional" class="form-label">Nacionalidade</label>
                        <p id="nacional">{{$person->nacional}}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="natural" class="form-label">Naturalidade</label>
                        <p id="natural">{{$person->natural}}</p>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4 d-flex justify-content-end align-items-start">
                <?php if (!empty($person->image_bs64) && !empty($person->tipo_img)): ?>
                    <?php
                    $base64Image = trim($person->image_bs64);
                    $base64Image = str_replace(array("\r", "\n", " "), "", $base64Image);
                    $imageData = 'data:image/' . htmlspecialchars($person->tipo_img) . ';base64,' . htmlspecialchars($base64Image);
                    ?>
                <a href="<?php echo $imageData; ?>" target="_blank" rel="noopener noreferrer"
                   title="Abrir imagem em nova aba">
                    <img src="<?php echo $imageData; ?>" alt="Imagem do cadastro"
                         class="img-fluid" style="width: 250px; height: 250px;">
                </a>
                <?php else: ?>
                <p>Imagem não disponível.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label for="observacao" class="form-label">Observação</label>
            <p id="observacao" style="white-space: pre-wrap; margin-top: 10px;">{!! $person->observação ?? '' !!}</p>
        </div>
    </div>
</div>
