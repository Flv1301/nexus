<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Busca cidades por UF usando a API do IBGE
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCitiesByUF(Request $request): JsonResponse
    {
        try {
            $uf = $request->input('uf');
            
            if (!$uf) {
                return response()->json([
                    'success' => false,
                    'message' => 'UF é obrigatório'
                ], 400);
            }

            // API do IBGE para buscar municípios por UF
            $response = Http::timeout(10)->get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$uf}/municipios");
            
            if ($response->successful()) {
                $cities = $response->json();
                
                // Formatar os dados para retorno
                $formattedCities = collect($cities)->map(function ($city) {
                    return [
                        'id' => $city['id'],
                        'nome' => $city['nome']
                    ];
                })->sortBy('nome')->values();

                return response()->json([
                    'success' => true,
                    'cities' => $formattedCities
                ]);
                
            } else {
                throw new \Exception('Erro ao buscar cidades na API do IBGE');
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar cidades por UF: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Busca cidades por nome (para autocomplete)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchCities(Request $request): JsonResponse
    {
        try {
            $search = $request->input('search');
            $uf = $request->input('uf');
            
            if (!$search || strlen($search) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Digite pelo menos 2 caracteres'
                ], 400);
            }

            $url = "https://servicodados.ibge.gov.br/api/v1/localidades/municipios";
            
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $cities = $response->json();
                
                // Filtrar por nome e UF se especificado
                $filteredCities = collect($cities)->filter(function ($city) use ($search, $uf) {
                    $nameMatch = stripos($city['nome'], $search) !== false;
                    $ufMatch = !$uf || $city['microrregiao']['mesorregiao']['UF']['sigla'] === strtoupper($uf);
                    
                    return $nameMatch && $ufMatch;
                })->map(function ($city) {
                    return [
                        'id' => $city['id'],
                        'nome' => $city['nome'],
                        'uf' => $city['microrregiao']['mesorregiao']['UF']['sigla']
                    ];
                })->take(10)->values(); // Limitar a 10 resultados

                return response()->json([
                    'success' => true,
                    'cities' => $filteredCities
                ]);
                
            } else {
                throw new \Exception('Erro ao buscar cidades na API do IBGE');
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar cidades: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ], 500);
        }
    }
} 