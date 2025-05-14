<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 08/03/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\APIs;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CortexApi
{
    /** @var string */
    private static string $endpoint = 'https://pessoas.azurewebsites.net/';
    /** @var string */
    private static string $endpointAuth = 'https://pessoas.azurewebsites.net/login';
    /** @var string */
    private static string $endpointPhysicalPerson = 'https://pessoas.azurewebsites.net/pessoafisica/';
    /** @var string */
    private static string $endpointBnmp = 'https://pessoas.azurewebsites.net/bnmp/';
    /** @var string */
    private static string $endpointCNPJ = 'https://pessoas.azurewebsites.net/pessoajuridica/';
    /** @var string */
    private static string $endpointPlate = 'https://veiculos.azurewebsites.net/emplacamentos/placa/';
    /** @var string */
    private static string $endpointAlertISP = 'https://veiculos.azurewebsites.net/';
    /** @var string */
    private static string $endpointAlert = 'https://veiculos.azurewebsites.net/';
    /** @var string */
    private static string $endpointMoviment = 'https://veiculos.azurewebsites.net/movimentos/placa/';

    /**
     * @var string|mixed
     */
    private string $token = '';
    private Authenticatable $user;

    public function __construct()
    {
        try {
            $this->user = Auth::user();
            $response = Http::post(self::$endpointAuth, [
                    'email' => env('CORTEX_API_EMAIL'),
                    'senha' => env('CORTEX_API_KEY')
                ]
            );

            if ($response->ok()) {
                $this->token = $response->headers()['Token'][0];
            }

            return $this;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param string $cpf
     * @return array|mixed
     */
    public function personSearchRenach(string $cpf): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpoint . 'renanch/cpf/' . $cpf)->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param int $id
     * @return array|mixed
     */
    public function personSearchBnmpId(int $id): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointBnmp . $id)->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $name
     * @return array|mixed
     */
    public function personSearchBnmpName(string $name): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointBnmp . $name . '/listagem')->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $cpf
     * @return array|mixed
     */
    public function personSearchBnmpCpf(string $cpf): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointBnmp . 'cpf/listagem', ['cpf' => $cpf])->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $name
     * @param string $birthDate
     * @return mixed
     */
    public function personSearchBnmpNameAndBirthDate(string $name, string $birthDate): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointBnmp . 'listagem', ['nome' => $name, 'dataNascimento' => $birthDate])->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $name
     * @param string $mother
     * @return mixed
     */
    public function personSearchBnmpNameAndMother(string $name, string $mother): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointBnmp . 'nomemae/listagem', ['nome' => $name, 'nomeMae' => $mother])->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $cpf
     * @return array|int[]|mixed
     */
    public function personSearchCPF(string $cpf): mixed
    {
        $cpf = Str::padLeft($cpf, 11, '0'  );

        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointPhysicalPerson . $cpf)->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $name
     * @param string $birthDate
     * @return mixed
     */
    public function personSearchNameAndBirthDate(string $name, string $birthDate): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(
                self::$endpointPhysicalPerson . '/listagem',
                ['nome' => $name, 'dataNascimento' => Carbon::createFromFormat('d/m/Y', $birthDate)->toDateString()]
            )->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $name
     * @param string $mother
     * @return mixed
     */
    public function personSearchNameAndMother(string $name, string $mother): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf,
            ])->get(self::$endpointPhysicalPerson . '/nomemae/listagem', ['nome' => $name, 'nomeMae' => $mother])->json(
            );
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $cnpj
     * @return mixed
     */
    public function personSearchCNPJ(string $cnpj): mixed
    {
        if ($this->token !== '') {
            return Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf
            ])->get(self::$endpointCNPJ . $cnpj)->json();
        }

        return ['status' => 401, 'message' => 'Usuário sem autorização!', 'error' => 'Unauthorization'];
    }

    /**
     * @param string $plate
     * @return mixed
     */
    public function vehicleSearch(string $plate): mixed
    {
        try {
            $response = Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf
            ])->get(self::$endpointPlate . $plate);

            return ['status' => $response->status(), 'data' => $response->json()];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return ['status' => 500, 'data' => 'Erro no servidor!'];
        }
    }

    /**
     * @param string $plate
     * @return mixed
     */
    public function vehicleSearchMoviment(string $plate): mixed
    {
        try {
            $response = Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'usuario' => $this->user->cpf
            ])->get(self::$endpointMoviment . $plate);

            return ['status' => $response->status(), 'data' => $response->json()];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return ['status' => 500, 'data' => 'Erro no servidor!'];
        }
    }

    /**
     * @param string $plate
     * @return array
     */
    public function vehiclePlateAndMoviment(string $plate): array
    {
        $response = $this->vehicleSearch($plate);
        $status = $response['status'];
        $data = $response['data'];

        if ($status == 200) {
            $responseMoviments = $this->vehicleSearchMoviment($plate);
            $data['moviments'] = $responseMoviments['status'] == 200 ? $responseMoviments['data'] : [];
        }

        return ['status' => $status, 'data' => $data];
    }
}
