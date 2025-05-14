<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Services;

use App\APIs\CacadorRoraimaApi;
use App\APIs\CortexApi;
use App\APIs\ProdepaApi;
use App\APIs\SeducAPI;
use App\Models\Data\Address;
use App\Models\Dpa\Dpa;
use App\Models\Equatorial;
use App\Models\Galton\Galton;
use App\Models\Person\Person;
use App\Models\Polinter\Polinter;
use App\Models\Seap\Seap;
use App\Models\Seap\SeapVisitor;
use App\Models\Sisp\Bop;
use App\Models\Srh\Srh;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PersonSearchService
{
    /**
     * @param $id
     * @return mixed
     */
    public function person($id): mixed
    {
        return Person::find($id);
    }

    /**
     * @param $id
     * @return Collection
     */
    public function sisp($id): Collection
    {
        return collect([Bop::find($id)]);
    }

    /**
     * @param $cpf
     * @return array|int[]|mixed|string[]
     */
    public function cortex($cpf): mixed
    {
        $response = (new CortexApi())->personSearchCPF($cpf);

        if (array_key_exists('error', $response)) {
            return ['error' => 'Não foi possível conectar a base de dados do Cortex!'];
        }
        return $response;
    }

    /**
     * @param int $id
     * @return array|mixed|string[]
     */
    public function bnmp(int $id): mixed
    {
        $response = (new CortexApi())->personSearchBnmpId($id);
        if (array_key_exists('error', $response)) {
            return ['error' => 'Não foi possível conectar a base de dados do BNMP'];
        }
        return $response;
    }

    /**
     * @param $rg
     * @return mixed|string[]
     */
    public function prodepa($rg): mixed
    {
        $response = (new ProdepaApi())->documentSearch($rg);
        if (array_key_exists('error', $response)) {
            return ['error' => 'Não foi possível conectar a base de dados do Prodepa!'];
        }
        $response[0]['address'] = $this->pregMatchAddress($response[0]['endereco']);
        return $response[0];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function srh($id): mixed
    {
        return Srh::find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function seap($id): mixed
    {
        return Seap::find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function seap_visitante($id): mixed
    {
        return SeapVisitor::find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function galton($id): mixed
    {
        return Galton::find($id);
    }

    public function polinter($id): mixed
    {
        return Polinter::find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function dpa($id): mixed
    {
        return Dpa::find($id);
    }

    public function equatorial($id): mixed
    {
        return Equatorial::find($id);
    }

    public function cacador($id): mixed
    {
        $cassadorApi = new CacadorRoraimaApi();

        $response = $cassadorApi->personShow($id);

        if (!$response->isOk()) {
            return ['error' => 'Não foi possível conectar à base de dados do cacador!'];
        }

        $responseImg = $cassadorApi->personImage($id);

        $data = $response->getData(true);
        $personData = $data[1] ?? [];

        if ($responseImg->isOk()) {
            $dataImg = $responseImg->getData(true);
            if (isset($dataImg['nome_imagem_pessoa'], $dataImg['imagem_pessoa'])) {
                $personData['tipo_img'] = strtolower(pathinfo($dataImg['nome_imagem_pessoa'], PATHINFO_EXTENSION));
                $personData['image_bs64'] = $dataImg['imagem_pessoa'];
            }
        }
        $personData['nascimento'] = Carbon::createFromFormat('Y-m-d', $personData['nascimento'])->format('d/m/Y');

        return (object)$personData;
    }

    /**
     * @param $id
     * @return array|mixed|string[]
     */
    public function seduc($id): mixed
    {
        $nameAndMother = explode('|', $id);
        $response = (new SeducAPI())->searchStudents($nameAndMother[0], $nameAndMother[1]);

        if (!$response && array_key_exists('error', $response)) {
            return ['error' => 'Não foi pissível conectar a base de dados da Seduc!'];
        }

        unset($response['status']);

        return array_shift($response);
    }

    /**
     * @param $string
     * @return Address
     */
    protected function pregMatchAddress($string): Address
    {
        $address = new Address();
        $expression = '/^(.*?)\s+(\d+)\s+(.*?)\s+(.*?)\s+(.*?)$/';
        if (preg_match($expression, $string, $matches)) {
            $address->address = $matches[1];
            $address->number = $matches[2];
            $address->district = $matches[3];
            $address->city = $matches[4];
            $address->uf = $matches[5];
        }
        return $address;
    }
}
