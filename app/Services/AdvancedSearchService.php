<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Services;

use App\Models\Seap\Seap;
use App\Models\Sisp\Bop;

class AdvancedSearchService
{
    /**
     * @param $id
     * @return mixed
     */
    public function sisp($id): mixed
    {
        $sisp = Bop::with(['bopenv', 'boprel'])->findOrFail($id);
        $can = auth()->user()->can('sisp_sigiloso');
        if ($sisp->sigiloso && !$can) {
            $columns = $sisp->only(['dt_registro', 'n_bop', 'unidade_responsavel', 'sigiloso']);
            return new Bop($columns);
        }
        $sisp->can = $can;
        return $sisp;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function seap($id): mixed
    {
        $seap = Seap::with([
            'photos:id_preso,presofoto_fotobin',
            'peculiaritys:id_preso,descricao,orientacao,tipo,fotos_fotobin',
            'moviments:id_preso,movimentacao_data,tipomovimentacao_descricao,movimentacaoentradatipificacaopenal_descricao,movimentacaoobservacao_descricao',
            'documents',
            'visitorMoviments:id_preso,id_visitante,visitante_nome,parentesco_descricao',
        ])->find($id);

        if ($seap) {
            $this->peculiarityConvert($seap);
            $this->photosSeap($seap);
        }

        return $seap;
    }

    /**
     * @param $seap
     * @return void
     */
    private function peculiarityConvert($seap): void
    {
        $seap->peculiaritys = $seap->peculiaritys->map(function ($peculiaritys) {
            if ($peculiaritys->fotos_fotobin) {
                $modalId = 'modal-' . uniqid();
                $photo = base64_encode(stream_get_contents($peculiaritys->fotos_fotobin));

                $peculiaritys->fotos_fotobin = "
                    <div>
                        <a href='#' label='Open Modal' class='open-modal-link' data-toggle='modal' data-target='#{$modalId}'>Visualizar</a>
                    </div>
                    <div class='modal fade' id='{$modalId}' tabindex='-1' role='dialog' aria-labelledby='ModalSeapPhoto'>
                        <div class='modal-dialog modal-lg' role='document'>
                            <div class='modal-content'>
                                <div class='modal-body text-center'>
                                    <img width='400' src='data:image/jpeg;base64,{$photo}' alt='Foto Preso'/>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
            }
            unset($peculiaritys->id_preso);
            return $peculiaritys;
        });
    }

    /**
     * @param $seap
     * @return void
     */
    private function photosSeap($seap): void
    {
        $seap->photos = $seap->photos->map(function ($seap) {
            if ($seap->presofoto_fotobin) {
                $modalId = 'modal-' . uniqid();
                $photoContent = stream_get_contents($seap->presofoto_fotobin);
                $photo = base64_encode($photoContent);
                $seap->presofoto_fotobin = "
                <a href='#' label='Open Modal' data-toggle='modal' data-target='#{$modalId}'>
                    <img class='img-thumbnail' width='200px' height='200px' src='data:image/jpeg;base64,{$photo}' alt='Foto Preso'/>
                </a>
                <div class='modal fade' id='{$modalId}' tabindex='-1' role='dialog' aria-labelledby='ModalSeapPhoto'>
                    <div class='modal-dialog modal-lg' role='document'>
                        <div class='modal-content'>
                            <div class='modal-body text-center'>
                                <img width='400' src='data:image/jpeg;base64,{$photo}' alt='Foto Preso'/>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            unset($seap->id_preso);
            return $seap;
        });
    }
}
