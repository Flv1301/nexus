<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Facebook;

use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Helpers\Arr;
use App\Models\Files\Facebook\Facebook;
use App\Services\Socialmedias\FacebookIpService;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class FacebookHtmlAccessLogLayout extends HtmlCaseDocumentAbstract
{

    /**
     * @throws FileNotFoundException
     */
    protected function getPropertiesName(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][2]);

            if (!$parameter->count()) {
                return collect();
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });
            $data = Arr::flatten($data);
            $pattern = '/First(.+?)MiddleLast(.+)/';
            $properties = [];

            if (preg_match($pattern, $data['name'], $matches)) {
                $properties['first_name'] = $matches[1];
                $properties['last_name'] = $matches[2];
            }

            return collect($properties);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    protected function getPropertiesEmail(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][3]);

            if (!$parameter->count()) {
                return collect();
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });

            return collect(end($data));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    protected function getPropertiesVanity(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][4]);

            if (!$parameter->count()) {
                return collect();
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });

            return collect(end($data));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    protected function getPropertiesRegistration(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][5]);

            if (!$parameter->count()) {
                return collect();
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });

            return collect(end($data));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    protected function getPropertiesRegistrationIp(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][6]);

            if (!$parameter->count()) {
                return collect();
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });

            return collect(end($data));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    protected function getPropertiesPhoneNumbers(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][7]);

            if (!$parameter->count()) {
                return collect();
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });

            $pattern = '/\+(\d+) Cell Verified on (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} UTC)/';
            $phoneNumbers = [];

            if (preg_match_all($pattern, $data[1]['phone_numbers'], $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $phoneNumbers[] = [
                        'number' => $match[1],
                        'phone_number_verified' => $match[2]
                    ];
                }
            }

            return collect($phoneNumbers);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    /**
     * @return array
     * @throws FileNotFoundException
     */
    public function logIpAccess(): array
    {
        try {
            $crawler = new Crawler($this->extract[0][3]);
            $logIpAccessData = [];

            foreach ($crawler->filter('.div_table > div > div > .div_table') as $node) {
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

    /**
     * @return bool|Facebook
     * @throws Exception
     */
    public function store(): bool|Facebook
    {
        try {
            $parameter = $this->getParameters('Facebook');
            $propertiesName = $this->getPropertiesName();
            $propertiesEmail = $this->getPropertiesEmail();
            $propertiesVanity = $this->getPropertiesVanity();
            $propertiesRegistration = $this->getPropertiesRegistration();
            $propertiesRegistrationIp = $this->getPropertiesRegistrationIp();
            $parameter = array_merge(
                $parameter->all(),
                $propertiesName->all(),
                $propertiesEmail->all(),
                $propertiesVanity->all(),
                $propertiesRegistration->all(),
                $propertiesRegistrationIp->all()
            );

            $propertiesPhoneNumbers = $this->getPropertiesPhoneNumbers();

            $this->checkAndDelete($parameter, 'facebook_access_log');
            $logIpAccess = $this->logIpAccess();

            $facebook = new Facebook($parameter->all());
            $facebook->name = 'LOG DE ACESSO FACEBOOK';
            $facebook->extension = 'html';
            $facebook->view = 'facebook_access_log';
            $facebook->save();

            if ($logIpAccess) {
                $facebook->acesslogIpAddress()->createMany($logIpAccess);
            }

            $facebookService = new FacebookIpService($facebook);
            $facebookService->findIpAndStore();
//            WhatsappEvent::dispatch($whatsapp);

            return $facebook;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            if (isset($facebook->id)) {
                $facebook->delete();
            }

            throw new Exception($exception->getMessage());
        }
    }
}
