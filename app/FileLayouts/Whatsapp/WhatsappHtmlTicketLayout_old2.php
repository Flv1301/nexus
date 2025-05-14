<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 03/2024
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\FileLayouts\Whatsapp;

use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Helpers\Arr;
use App\Models\Files\Whatsapp\Whatsapp;
use App\Models\Files\Whatsapp\WhatsappCall;
use App\Models\Files\Whatsapp\WhatsappCallEvent;
use App\Models\Files\Whatsapp\WhatsappCallEventParticipant;
use App\Services\Socialmedias\WhatsappIpService;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Layout HTML arquivo de bilhetagem Whatsapp.
 */
class WhatsappHtmlTicketLayout extends HtmlCaseDocumentAbstract
{

    /**
     * @return Collection
     * @throws FileNotFoundException
     */
    public function messages(): Collection
    {
        try {
            $messages = new Crawler($this->extract[0][1]);

            if (!$messages->count()) {
                return collect();
            }

            $data = $messages->filterXPath('//body/div/div/div')->each(function ($node) {
                return $node->html();
            });

            unset($data[0]);
            $messages = new Crawler($data[1]);
            $data = $messages->filterXPath('//body/div/div')->each(function ($node) {
                return $node->html();
            });
            $data = array_filter($data);
            $messages = collect();

            foreach ($data as $message) {
                $node = new Crawler($message);
                $newData = $node->filter('div > .t.i')->each(function ($node) {
                    $parentText = $node->text();
                    $childText = $node->filter('.m')->text();
                    $parentTextOnly = Str::snake(str_replace($childText, '', $parentText));

                    return [$parentTextOnly => $childText];
                });
                $newData = Arr::flatten($newData);
                $messages->push($newData);
            }

//            $data = $messages->filterXPath('//body/div[2]/div/div/div/div')->each(function ($node) {
//                $data = $node->filterXPath('//div')->children()->children()->each(function ($node) {
//                    $data = $node->filter('.div_table')->each(function ($node) {
//                        $parentText = $node->text();
//                        $childText = $node->filter('.div_table > div > div')->text();
//                        $parentTextOnly = Str::snake(str_replace($childText, '', $parentText));
//
//                        return [$parentTextOnly => $childText];
//                    });
//
//                    return Arr::flatten($data);
//                });
//                return Arr::flatten($data);
//            });

            return $messages;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
        }
    }

    /**
     * @return Collection
     * @throws FileNotFoundException
     */
    public function calls(): Collection
    {
        try {
            $calls = new Crawler($this->extract[0][2]);
            if (!$calls->count()) {
                return collect();
            }

            $data = $calls->filterXPath('//body/div/div/div/div')->each(function ($node) {
                return $node->html();
            });

            unset($data[0]);
            $calls = new Crawler($data[1]);
            $data = $calls->filterXPath('//body/div/div/div/div')->each(function ($node) {
                return $node->html();
            });
            $calls = collect();

            foreach ($data as $nodes) {
                $node = new Crawler($nodes);
                $call = $node->filterXPath('//body/div/div')->each(function ($node) {
                    $parentText = $node->text();
                    $childText = $node->filter('.m')->text();
                    $parentTextOnly = Str::snake(str_replace($childText, '', $parentText));

                    return [$parentTextOnly => $childText];
                });
                $call = Arr::flatten($call);
                unset($call[2]);
                $events = $node->filterXPath('//body/div/div')->last()->filter('div > .t.i')
                    ->each(function ($node) {
                        $parentText = $node->text();
                        $childText = $node->filter('.m')->text();
                        $parentText = Str::snake(str_replace($childText, '', $parentText));

                        if ($parentText == 'participants') {
                            $participants = $node->filter('div > .t.i')->each(function ($node) {
                                $parentText = $node->text();
                                $childText = $node->filter('.m')->text();
                                $parentText = Str::snake(str_replace($childText, '', $parentText));
                                return [$parentText => $childText];
                            });

                            return [$parentText => $participants];
                        }
                        return [$parentText => $childText];
                    });

                $events = $this->divideInBlock($events, 'type');
                $call['events'] = $events;
                $calls->push($call);
            }

            return $calls;

//            return collect(
//                $calls->filterXPath('//body/div[2]/div/div/div/div')->each(function ($node) {
//                    $call = $node->filterXPath('//div/div/div')->children()->children()->each(function ($node) {
//                        $parentText = $node->text();
//                        $childText = $node->filter('.div_table > div > div')->text();
//                        $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();
//                        return [$parentTextOnly => $childText];
//                    });
//                    unset($call[2]);
//                    $call = Arr::flatten($call);
//
//                    $events = $node->filter('div > div > div > div')->children()->last()->filter('.div_table > div')
//                        ->children()->filter('.div_table > div')->each(function ($node) {
//                            $parentText = $node->text();
//                            $childText = $node->children()->text();
//                            $parentTextOnly = Str::snake(str_replace($childText, '', $parentText));
//                            return [$parentTextOnly => $childText];
//                        });
//
//                    $events = array_filter($events, function ($node) {
//                        return !array_key_exists("", $node);
//                    });
//                    $events = $this->divideInBlock($events, 'Type');
//                    $call['events'] = $events;
//                    return $call;
//                })
//            );
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
        }
    }

    /**
     * @return Whatsapp
     * @throws Exception
     */
    public
    function store(): Whatsapp
    {
        try {
            $parameter = $this->getParameters('WhatsApp');
            $this->checkAndDelete($parameter, 'whatsapp_ticket');
            $messages = $this->extract()->messages();
            $calls = $this->extract()->calls();

            $whatsapp = new Whatsapp($parameter->all());
            $whatsapp->name = 'BILHETAGEM WHATSAPP';
            $whatsapp->extension = 'html';
            $whatsapp->view = 'whatsapp_ticket';
            $whatsapp->save();

            if ($calls->count()) {
                $this->whatsappCalls($calls->all(), $whatsapp);
            }

            if ($messages->count()) {
                $whatsapp->messages()->createMany($messages->all());
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

    /**
     * @param array $calls
     * @param Whatsapp $whats
     * @return void
     */
    private function whatsappCalls(array $calls, Whatsapp $whats): void
    {
        foreach ($calls as $call) {
            $whatsappCall = $whats->calls()->create($call);

            if ($call['events']) {
                $this->events($call['events'], $whatsappCall);
            }
        }
    }

    /**
     * @param array $events
     * @param WhatsappCall $whatsappCall
     * @return void
     */
    private function events(array $events, WhatsappCall $whatsappCall): void
    {
        foreach ($events as $event) {
            $callEvent = new WhatsappCallEvent();
            $callEvent->whatsapp_call_id = $whatsappCall->id;
            $callEvent->type = array_key_exists('type', $event)
                ? $event['type'] : null;
            $callEvent->timestamp = array_key_exists('timestamp', $event)
                ? $event['timestamp'] : null;
            $callEvent->from = array_key_exists('from', $event)
                ? $event['from'] : null;
            $callEvent->to = array_key_exists('to', $event)
                ? $event['to'] : null;
            $callEvent->from_ip = array_key_exists('from_ip', $event)
                ? $event['from_ip'] : null;
            $callEvent->from_port = array_key_exists('from_port', $event)
                ? $event['from_port'] : null;
            $callEvent->media_type = array_key_exists('media_type', $event)
                ? $event['media_type'] : null;
            $callEvent->save();
            if (array_key_exists('participants', $event)) {
                $this->participants($event['participants'], $callEvent);
            }
        }
    }

    /**
     * @param array $participants
     * @param WhatsappCallEvent $callEvent
     * @return void
     */
    private function participants(array $participants, WhatsappCallEvent $callEvent): void
    {
        foreach ($participants as $participant) {
            $eventParticipant = new WhatsappCallEventParticipant();
            $eventParticipant->whatsapp_call_event_id = $callEvent->id;
            $eventParticipant->phone_number = array_key_exists('phone_number', $participant)
                ? $participant['phone_number'] : null;
            $eventParticipant->state = array_key_exists('state', $participant)
                ? $participant['state'] : null;
            $eventParticipant->platform = array_key_exists('platform', $participant)
                ? $participant['platform'] : null;
            $eventParticipant->save();
        }
    }
}
