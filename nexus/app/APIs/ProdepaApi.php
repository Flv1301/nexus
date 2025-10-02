<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\APIs;

use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;

class ProdepaApi
{
    /**
     * @var SoapClient
     */
    private SoapClient $client;
    /**
     * @var string
     */
    private string $bodyWsdl;
    /**
     * @var string
     */
    private static string $url_wsdl = "https://www.sistemas.pa.gov.br/identificacaocivil/CadastroCivilService/ConsultaCadastroCivilServiceBean?wsdl";

    public function __construct()
    {
        try {
            $username = env('PRODEPA_API_USER');
            $password = env('PRODEPA_API_KEY');
            $options = array(
                'login' => $username,
                'password' => $password,
                'soap_version' => SOAP_1_1,
                'trace' => 1,
                'exceptions' => true,
            );
            $this->client = new SoapClient(self::$url_wsdl, $options);
            return $this;
        } catch (SoapFault $exception) {
            Log::error($exception->getMessage());
            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $name
     * @param string $lastName
     * @param string $birth_date
     * @param string $father
     * @param string $mother
     * @param string $rg
     * @return mixed
     */
    public function civilIdentification(
        string $name,
        string $lastName,
        string $birth_date,
        string $father = '',
        string $mother = '',
        string $rg = ''
    ): mixed {
        try {
            $params = [
                'nome' => $name,
                'sobrenome' => $lastName,
                'dataNascimento' => date('dmY', strtotime($birth_date)),
                'nomePai' => $father,
                'nomeMae' => $mother,
                'numeroRg' => $rg,
            ];

            $this->body('buscarDadosCadastroCivil', $params);
            $response = $this->doRequest();
            $data = [];
            if ($response) {
                $xml = simplexml_load_string($response);
                foreach ($xml->xpath('//item') as $item) {
                    $temp = [];
                    foreach ($item->children() as $child) {
                        $temp[$child->getName()] = (string)trim($child);
                    }
                    $data[] = $temp;
                }
            }
            return $data;
        } catch (SoapFault|Exception $exception) {
            Log::error($exception->getMessage());
            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $rg
     * @param string $cpf
     * @return array
     */
    public function documentSearch(string $rg = '', string $cpf = ''): array
    {
        try {
            $params = ['rg' => $rg, 'cpf' => $cpf];
            $this->body('consultarPorDocumento', $params);
            $response = $this->doRequest();
            $data = [];
            if ($response) {
                $xml = simplexml_load_string($response);
                foreach ($xml->xpath('//item') as $item) {
                    $temp = [];
                    foreach ($item->children() as $child) {
                        $temp[$child->getName()] = (string)trim($child);
                    }
                    $data[] = $temp;
                }
            }
            return $data;
        } catch (SoapFault|Exception $exception) {
            Log::error($exception->getMessage());
            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $name
     * @param string $lastname
     * @param string $mother
     * @param string $father
     * @return array
     */
    public function nameSearch(string $name, string $lastname, string $mother = '', string $father = ''): array
    {
        try {
            $params = ['nome' => $name, 'sobrenome' => $lastname, 'nome_mae' => $mother, 'nome_pai' => $father];
            $this->body('consultarPorNome', $params);
            $response = $this->doRequest();
            $data = [];
            if ($response) {
                $xml = simplexml_load_string($response);
                foreach ($xml->xpath('//item') as $item) {
                    $temp = [];
                    foreach ($item->children() as $child) {
                        $temp[$child->getName()] = (string)trim($child);
                    }
                    $data[] = $temp;
                }
            }
            return $data;
        } catch (SoapFault|Exception $exception) {
            Log::error($exception->getMessage());
            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @return string|null
     */
    public function doRequest(): ?string
    {
        return $this->client->__doRequest($this->bodyWsdl, self::$url_wsdl, '', 1);
    }

    /**
     * @param string $method
     * @param array $params
     * @return void
     */
    private function body(string $method, array $params): void
    {
        $body = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.identificacaocivil.prodepa.pa.gov.br/">';
        $body .= '<soapenv:Header/>';
        $body .= '<soapenv:Body>';
        $body .= "<ser:{$method}>";
        foreach ($params as $key => $value) {
            $body .= "<$key>$value</$key>";
        }
        $body .= "</ser:{$method}>";
        $body .= '</soapenv:Body>';
        $body .= '</soapenv:Envelope>';
        $this->bodyWsdl = $body;
    }
}
