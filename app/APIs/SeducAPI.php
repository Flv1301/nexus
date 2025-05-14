<?php

namespace App\APIs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SeducAPI
{
    private static string $endpointStudent = 'https://www.seduc.pa.gov.br/api_pcpa/0a8db46bf79299f2b6769f1e6c06c535/aluno/nome/';
    /** @var string */
    private static string $endPoint = 'https://www.seduc.pa.gov.br/api_pcpa/';
    /** @var string|mixed */
    private static string $token;

    public function __construct()
    {
        self::$token = env('SEDUC_API_KEY', '');
    }

    /**
     * @param $name
     * @param $optionalParameters
     * @return array|mixed
     */
    public static function getStudentByName($name, $optionalParameters): mixed
    {
        try {
            $name = self::replaceSpaceToPlus($name);
            $url = SeducAPI::$endpointStudent . $name;
            if ($optionalParameters != '') {
                $url .= $optionalParameters;
            }

            return Http::timeout(30)->withHeaders(['endpoint' => SeducAPI::$endpointStudent,])
                ->withOptions(["verify" => false])->get($url)->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $name
     * @param string $mother
     * @param string $father
     * @return mixed
     */
    public function searchStudents(string $name, string $mother = '', string $father = ''): mixed
    {
        try {
            $name = $this->replaceSpaceToPlus($name);
            $mother = $this->replaceSpaceToPlus($mother);
            $father = $this->replaceSpaceToPlus($father);
            $url = self::$endPoint . self::$token . '/aluno/nome/' . $name . ($mother ? '/nomemae/' . $mother : '') . ($father ? '/nomepai/' . $father : '');

            return Http::timeout(15)->get($url)->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $name
     * @param string $mother
     * @return mixed
     */
    public function searchStudentsWithMother(string $name, string $mother): mixed
    {
        try {
            $name = $this->replaceSpaceToPlus($name);
            $mother = $this->replaceSpaceToPlus($mother);
            $url = self::$endPoint . self::$token . '/aluno/nome/' . $name . '/nomemae/' . $mother;

            return Http::timeout(15)->get($url)->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $name
     * @param string $father
     * @return mixed
     */
    public function searchStudentsWithFather(string $name, string $father): mixed
    {
        try {
            $name = $this->replaceSpaceToPlus($name);
            $father = $this->replaceSpaceToPlus($father);
            $url = self::$endPoint . self::$token . '/aluno/nome/' . $name . '/nomepai/' . $father;

            return Http::timeout(15)->get($url)->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $mother
     * @return mixed
     */
    public function searchStudentsForMother(string $mother): mixed
    {
        try {
            $mother = $this->replaceSpaceToPlus($mother);
            $url = self::$endPoint . self::$token . '/aluno/nomemae/' . $mother;

            return Http::timeout(15)->get($url)->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $father
     * @return mixed
     */
    public function searchStudentsForFather(string $father): mixed
    {
        try {
            $father = $this->replaceSpaceToPlus($father);
            $url = self::$endPoint . self::$token . '/aluno/nomepai/' . $father;

            return Http::timeout(15)->get($url)->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @param string $value
     * @return array|string
     */
    private function replaceSpaceToPlus(string $value): array|string
    {
        return str_replace(' ', '+', $value);
    }
}
