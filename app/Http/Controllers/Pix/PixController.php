<?php

namespace App\Http\Controllers\Pix;

use App\APIs\ApiBacenPix;
use App\Http\Controllers\Controller;
use App\Http\Requests\PixResquest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;

class PixController extends Controller
{
    private ApiBacenPix $pixClient;

    public function __construct(ApiBacenPix $pixClient)
    {
        $this->pixClient = $pixClient;
    }

    /**
     * Exibe a página de pesquisa de PIX.
     *
     * @return View
     */
    public function index(): View
    {
        return view('search.pix.index');
    }

    /**
     * Realiza a busca pela chave PIX ou CPF/CNPJ e formata o resultado.
     *
     * @param PixResquest $request
     * @return View
     */
    public function search(PixResquest $request): View
    {
        $pix = $request->input('key_pix');
        $motivation = $request->input('motivation');
        $searchType = $request->input('search_type');

        try {
            $response = $this->searchByType($searchType, $pix, $motivation);
            $data = json_decode($response, true);

            if (isset($data['error'])) {
                $result = ['error' => $data['error']];
            } else {
                $result = $this->formatDataByType($searchType, $data);
            }

            $this->logActivity($result, $searchType, $pix, $motivation);

            return view('search.pix.index', ['data' => $result, 'searchType' => $searchType]);
        } catch (ConnectionException $exception) {
            return view('search.pix.index', [
                'data' => ['error' => $exception->getMessage()],
                'searchType' => $searchType,
            ]);
        }
    }

    /**
     * Realiza a busca conforme o tipo de pesquisa.
     *
     * @param string $searchType
     * @param string $pix
     * @param string $motivation
     * @return array|string
     * @throws ConnectionException
     */
    private function searchByType(string $searchType, string $pix, string $motivation): array|string
    {
        return $searchType === 'pix'
            ? $this->pixClient->searchByPixKey($pix, $motivation)
            : $this->pixClient->searchByCpfCnpj($pix, $motivation);
    }

    /**
     * Formata os dados de acordo com o tipo de pesquisa.
     *
     * @param string $searchType
     * @param array $data
     * @return array
     */
    private function formatDataByType(string $searchType, array $data): array
    {
        return $searchType === 'pix'
            ? $this->formatPixKeyData($data)
            : $this->formatCpfCnpjData($data);
    }

    /**
     * Formata os dados da chave PIX.
     *
     * @param array $data
     * @return array
     */
    private function formatPixKeyData(array $data): array
    {
        return [
            'chave' => $data['chave'] ?? null,
            'tipoChave' => $data['tipoChave'] ?? null,
            'status' => $data['status'] ?? null,
            'dataAberturaReivindicacao' => $data['dataAberturaReivindicacao'] ?? null,
            'cpfCnpj' => $data['cpfCnpj'] ?? null,
            'nomeProprietario' => $data['nomeProprietario'] ?? null,
            'nomeFantasia' => $data['nomeFantasia'] ?? null,
            'banco' => !empty($data['participante']) ? $this->modifyParticipante($data['participante']) : null,
            'agencia' => $data['agencia'] ?? null,
            'numeroConta' => $data['numeroConta'] ?? null,
            'tipoConta' => $data['tipoConta'] ?? null,
            'dataAberturaConta' => $data['dataAberturaConta'] ?? null,
            'proprietarioDaChaveDesde' => $data['proprietarioDaChaveDesde'] ?? null,
            'dataCriacao' => $data['dataCriacao'] ?? null,
            'ultimaModificacao' => $data['ultimaModificacao'] ?? null,
            'eventosVinculo' => $this->formatEventosVinculo($data['eventosVinculo'] ?? [])
        ];
    }

    /**
     * Formata os dados de CPF/CNPJ.
     *
     * @param array $data
     * @return array
     */
    private function formatCpfCnpjData(array $data): array
    {
        $vinculosPix = $data['vinculosPix'] ?? [];

        foreach ($vinculosPix as &$vinculo) {
            if (isset($vinculo['participante'])) {
                $vinculo['participante'] = $this->modifyParticipante($vinculo['participante']);
            }

            if (isset($vinculo['eventosVinculo'])) {
                foreach ($vinculo['eventosVinculo'] as &$evento) {
                    if (isset($evento['participante'])) {
                        $evento['participante'] = $this->modifyParticipante($evento['participante']);
                    }
                }
            }
        }

        return $vinculosPix;
    }

    /**
     * Formata os eventos de vínculo, alterando a chave de participante para banco.
     *
     * @param array $eventosVinculo
     * @return array
     */
    private function formatEventosVinculo(array $eventosVinculo): array
    {
        foreach ($eventosVinculo as &$evento) {
            if (isset($evento['participante'])) {
                $evento['participante'] = $this->modifyParticipante($evento['participante']);
            }
        }
        return $eventosVinculo;
    }

    private function modifyParticipante(string $participante): string
    {
        $novoParticipante = config("data.bancos.{$participante}");
        return $novoParticipante ?? $participante;
    }

    /**
     *
     * @param array $result
     * @param string $searchType
     * @param string $pix
     * @param string $motivation
     * @return void
     */
    private function logActivity(array $result, string $searchType, string $pix, string $motivation): void
    {
        activity()->withProperties($result)
            ->event('search')
            ->useLog('Pesquisa Dados PIX')
            ->log("Tipo de pesquisa: {$searchType} - Chave: {$pix} - Motivo: {$motivation}");
    }
}
