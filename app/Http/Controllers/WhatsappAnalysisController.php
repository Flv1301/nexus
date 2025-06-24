<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\IPInfoService;
use Carbon\Carbon;

class WhatsappAnalysisController extends Controller
{
    public function index()
    {
        return view('whatsapp.analysis.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'whatsapp_file' => 'required|file|mimes:html,htm|max:10240', // 10MB max
            'analysis_name' => 'required|string|max:255'
        ]);

        try {
            $file = $request->file('whatsapp_file');
            $analysisName = $request->input('analysis_name');
            
            // Ler conteúdo do arquivo HTML
            $htmlContent = file_get_contents($file->path());
            
            // Processar o arquivo e extrair dados
            $analysisData = $this->processWhatsappFile($htmlContent, $analysisName);
            
            // Salvar arquivo no storage
            $fileName = 'whatsapp_analysis_' . time() . '.html';
            Storage::disk('local')->put('whatsapp_analyses/' . $fileName, $htmlContent);
            
            // Salvar dados da análise (você pode criar um model para isso)
            $analysisId = $this->saveAnalysis($analysisData, $fileName);
            
            return redirect()->route('whatsapp.show', $analysisId)
                ->with('success', 'Arquivo WhatsApp processado com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro no upload do WhatsApp: ' . $e->getMessage());
            return back()->with('error', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Aqui você carregaria os dados da análise do banco
        // Por enquanto, vou simular dados
        $analysis = $this->getAnalysisData($id);
        
        return view('whatsapp.analysis.show', compact('analysis'));
    }

    private function processWhatsappFile($htmlContent, $analysisName)
    {
        $data = [
            'name' => $analysisName,
            'processed_at' => now(),
            'messages' => [],
            'ips' => [],
            'phones' => [],
            'stats' => []
        ];

        // Extrair mensagens usando regex
        $messagePattern = '/<div[^>]*class="[^"]*message[^"]*"[^>]*>(.*?)<\/div>/is';
        preg_match_all($messagePattern, $htmlContent, $messages);

        // Extrair IPs
        $ipPattern = '/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b/';
        preg_match_all($ipPattern, $htmlContent, $ips);

        // Extrair números de telefone
        $phonePattern = '/\+?[\d\s\-\(\)]{10,}/';
        preg_match_all($phonePattern, $htmlContent, $phones);

        // Extrair timestamps
        $timePattern = '/\d{1,2}\/\d{1,2}\/\d{4}[\s,]+\d{1,2}:\d{2}(?::\d{2})?/';
        preg_match_all($timePattern, $htmlContent, $timestamps);

        $data['messages'] = array_slice($messages[0], 0, 100); // Limitar a 100 mensagens
        $data['ips'] = array_unique($ips[0]);
        $data['phones'] = array_unique($phones[0]);
        $data['timestamps'] = array_slice($timestamps[0], 0, 50);

        // Processar IPs para obter geolocalização
        if (!empty($data['ips'])) {
            $ipService = new IPInfoService();
            $ipService->store($data['ips']);
        }

        // Estatísticas básicas
        $data['stats'] = [
            'total_messages' => count($messages[0]),
            'unique_ips' => count($data['ips']),
            'unique_phones' => count($data['phones']),
            'date_range' => $this->getDateRange($timestamps[0])
        ];

        return $data;
    }

    private function getDateRange($timestamps)
    {
        if (empty($timestamps)) return null;

        $dates = [];
        foreach ($timestamps as $timestamp) {
            try {
                $dates[] = Carbon::createFromFormat('d/m/Y H:i', substr($timestamp, 0, 16));
            } catch (\Exception $e) {
                // Ignorar timestamps inválidos
            }
        }

        if (empty($dates)) return null;

        return [
            'start' => min($dates)->format('d/m/Y H:i'),
            'end' => max($dates)->format('d/m/Y H:i')
        ];
    }

    private function saveAnalysis($data, $fileName)
    {
        // Por enquanto, vou salvar em sessão
        // Idealmente você criaria um model WhatsappAnalysis
        $analysisId = uniqid();
        session()->put("whatsapp_analysis_{$analysisId}", [
            'id' => $analysisId,
            'file_name' => $fileName,
            'user_id' => Auth::id(),
            'data' => $data
        ]);

        return $analysisId;
    }

    private function getAnalysisData($id)
    {
        return session()->get("whatsapp_analysis_{$id}");
    }
} 