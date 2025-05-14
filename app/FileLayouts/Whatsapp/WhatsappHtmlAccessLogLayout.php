<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Whatsapp;

use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Models\Files\Whatsapp\Whatsapp;
use App\Services\Socialmedias\WhatsappIpService;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class WhatsappHtmlAccessLogLayout extends HtmlCaseDocumentAbstract
{

    public function extract(): bool|static
    {
        try {
            $this->html = $this->crawler->filter('#records')->html();

            return $this;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new FileNotFoundException('Não foi possível extrair dados do arquivo!');
        }
    }

    public function parameter(): Collection
    {
        try {
            $data = [];

            preg_match('/<div class="t i">Service<div class="m"><div>(.*?)<div class="p">/s', $this->html, $service);
            preg_match('/<div class="t i">Internal Ticket Number<div class="m"><div>(.*?)<div class="p">/s', $this->html, $ticketNumber);
            preg_match('/<div class="t i">Account Identifier<div class="m"><div>(.*?)<div class="p">/s', $this->html, $accountIdentifier);
            preg_match('/<div class="t i">Account Type<div class="m"><div>(.*?)<div class="p">/s', $this->html, $accountType);
            preg_match('/<div class="t i">Generated<div class="m"><div>(.*?)<div class="p">/s', $this->html, $generated);
            preg_match('/<div class="t i">Date Range<div class="m"><div>(.*?)<div class="p">/s', $this->html, $dateRange);

            $data['service'] = $service[1] ?? '';
            $data['ticket_number'] = $ticketNumber[1] ?? '';
            $data['account_identifier'] = $accountIdentifier[1] ?? '';
            $data['account_type'] = $accountType[1] ?? '';
            $data['generated'] = $generated[1] ?? '';
            $data['date_range'] = $dateRange[1] ?? '';

            return collect($data);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    public function getParameters(string $typeLayout): Collection
    {
        $parameter = $this->extract()->parameter();
        $service = $parameter->get('service');

        if ($service && $service !== $typeLayout) {
            throw new Exception('Layout para o arquivo é inválido!');
        }

        return $parameter;
    }

    public function extractTimeAndIp(): Collection
    {
        try {
            $patterns = [
                'time' => '/<div class="t i">Time<div class="m"><div>(.*?)<div class="p">/',
                'ip_address' => '/<div class="t i">IP Address<div class="m"><div>(.*?)<div class="p">/'
            ];

            $lines = explode('</div>', $this->html);
            $data = [];
            $currentEntry = [];

            foreach ($lines as $line) {
                if (preg_match($patterns['time'], $line, $matches)) {
                    if (!empty($currentEntry)) {
                        $data[] = $currentEntry;
                    }

                    $currentEntry = [
                        'time' => $matches[1],
                        'ip_address' => ''
                    ];
                } elseif (preg_match($patterns['ip_address'], $line, $matches)) {
                    if (!empty($currentEntry)) {
                        $currentEntry['ip_address'] = $matches[1];
                    }
                }
            }

            if (!empty($currentEntry)) {
                $data[] = $currentEntry;
            }

            return collect($data);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
        }
    }


    /**
     * @return array
     * @throws FileNotFoundException
     */
    public function logIpAccess(): array
    {
        try {
            $crawler = new Crawler($this->extract[0][5]);
//            dd($crawler->html());
            $logIpAccessData = [];

            foreach ($crawler->filter('body > div > div > div > div > div') as $node) {
                $pattern = '/(Time|IP Address)\s*(\d{4}(?:-\d{2}){2}\s+\d{2}(?::\d{2}){2}\s+\w{3,4}|(?:\d{1,3}\.){3}\d{1,3}|(?:[0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4})/';
                $text = $node->nodeValue;
                if (preg_match($pattern, $text, $matches)) {
                    $key = Str::snake(Str::lower($matches[1]));
                    $value = $matches[2];
                    $logIpAccessData[] = [$key => $value];
                }
            }

            return $this->divideInBlock($logIpAccessData, 'time');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('Dados de IP de acesso não localizado!.');
        }
    }

//    public function parameters(): Collection
//    {
//        try {
//            $parameter = new Crawler($this->extract[0][1]);
//
//            if (!$parameter->count()) {
//                throw new FileNotFoundException('Não foi possível localizar os parametros!.');
//            }
//
//            $data = $parameter->filter('body > div > div')->each(function ($node) {
//                $parentText = $node->text();
//                $childText = $node->filter('.m > div')->text();
//                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();
//
//                return [$parentTextOnly => $childText];
//            });
//
////            if (empty($data)) {
////                $data = $parameter->filter('body > div')->each(function ($node) {
////                    $parentText = $node->text();
////                    $childText = $node->filter('.t > div > div')->text();
////                    $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();
////
////                    return [$parentTextOnly => $childText];
////                });
////            }
//
//            $data = Arr::flatten($data);
//
//            return collect($data);
//        } catch (Exception $exception) {
//            Log::error($exception->getMessage());
//            throw new FileNotFoundException('O arquivo está vazio.');
//        }
//    }

    /**
     * @return bool|Whatsapp
     * @throws Exception
     */
    public function store(): bool|Whatsapp
    {
        try {
            $parameter = $this->getParameters('WhatsApp');
            $this->checkAndDelete($parameter, 'whatsapp_access_log');
            $logIpAccess = $this->extractTimeAndIp();

            $whatsapp = new Whatsapp($parameter->all());
            $whatsapp->name = 'LOG DE ACESSO WHATSAPP';
            $whatsapp->extension = 'html';
            $whatsapp->view = 'whatsapp_access_log';
            $whatsapp->save();

            if ($logIpAccess->count()) {
                $whatsapp->acesslogIpAddress()->createMany($logIpAccess->all());
            }

            $whatsService = new WhatsappIpService($whatsapp);
            $whatsService->findIpAndStore();
//            WhatsappEvent::dispatch($whatsapp);

            return $whatsapp;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            if (isset($whatsapp->id)) {
                $whatsapp->delete();
            }

            throw new Exception($exception->getMessage());
        }
    }
}
