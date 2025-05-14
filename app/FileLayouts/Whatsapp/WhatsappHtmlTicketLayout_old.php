<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 17/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\FileLayouts\Whatsapp;

use App\Events\WhatsappEvent;
use App\Helpers\Arr;
use App\Models\Files\Whatsapp\Whatsapp;
use App\Models\Files\Whatsapp\WhatsappCall;
use App\Models\Files\Whatsapp\WhatsappCallEvent;
use App\Models\Files\Whatsapp\WhatsappCallEventParticipant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Layout HTML arquivo de bilhetagem Whatsapp.
 */
class WhatsappHtmlTicketLayoutOld extends WhatsappLayoutAbstract
{
    /**
     * @return array|false
     */
    private function messageLogWhatsapp(): bool|array
    {
        if (!$this->crawler->filter('#property-message_log')->count()) {
            return false;
        }
        if (!$this->crawler->filter('#property-message_log > table > tr > td')->count()) {
            return [];
        }
        $message = $this->crawler->filter('#property-message_log > table > tr > td')
            ->last()
            ->children()
            ->each(function ($n) {
                $data = $n->children()
                    ->each(function ($n) {
                        $data = $n->filter('td')->children()->filter('tr')->each(function ($n) {
                            $th[] = Str::of($n->filter('th')->text())->snake();
                            $td[] = $n->filter('td')->text();
                            return array_combine($th, $td);
                        });
                        return Arr::flatten($data);
                    });
                return Arr::flatten($data);
            });
        return array_filter($message);
    }

    /**
     * @return array|false
     */
    protected function callLogWhatsapp(): bool|array
    {
        if (!$this->crawler->filter('#property-call_logs')->count()) {
            return false;
        }
        try {
            return ($this->crawler->filter('#property-call_logs > table')
                ->last()
                ->children()
                ->children()
                ->last()
                ->children()
                ->each(function ($n) {
                    if (!$n->filter('table')->count()) {
                        throw new \Exception('Whatsapp Ticket No Responsive Records Located');
                    }

                    $call = $n->filter('table')
                        ->filter('tr')
                        ->children()
                        ->last()
                        ->children()
                        ->each(function ($n) {
                            $th[] = Str::of($n->filter('th')->text())->snake();
                            $td[] = $n->filter('td')->text();
                            return array_combine($th, $td);
                        });
                    array_pop($call);
                    $events = $n->filter('table')
                        ->filter('tr')
                        ->children()
                        ->last()
                        ->children()
                        ->last()
                        ->children()
                        ->children()
                        ->last()
                        ->filter('tr')
                        ->each(function ($n) {
                            $th = (string)Str::of($n->filter('th')->text())->snake();
                            $td = $n->filter('td')->text();
                            $tag = [];
                            if (!in_array($th, ['phone_number', 'state', 'platform', 'participants'])) {
                                $tag[$th] = $td;
                            }
                            if ($th == 'participants') {
                                $tag['participants'] = $n->filter('tr')
                                    ->children()
                                    ->last()
                                    ->filter('tr')
                                    ->each(function ($n) {
                                        $th1 = (string)Str::of($n->filter('th')->text())->snake();
                                        $td1 = $n->filter('td')->text();
                                        $tag[$th1] = $td1;
                                        return $tag;
                                    });
                                $tag['participants'] = Arr::fllatenDuplicateKey($tag['participants']);
                            }
                            return $tag;
                        });
                    $array['call'] = Arr::flatten($call);
                    $array['events'] = Arr::fllatenDuplicateKey($events);

                    return $array;
                }));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());

            return [];
        }
    }

    /**
     * @return Whatsapp|false
     */
    public function store(): bool|Whatsapp
    {
        try {
            $homeWhats = $this->homeLogWhatsapp(1)->all();
            $identifier = $homeWhats['account_identifier'] ?? '';
            $generated = $homeWhats['generated'] ?? '';

            if (!$homeWhats || $this->verifyHome($identifier, $generated)) {
                return false;
            }

            $calls = $this->callLogWhatsapp();
            $messages = $this->messageLogWhatsapp();
            $whatsapp = new Whatsapp($homeWhats);
            $whatsapp->name = 'BILHETAGEM WHATSAPP';
            $whatsapp->extension = 'html';
            $whatsapp->view = 'whatsapp_ticket';
            $whatsapp->save();

            if ($calls) {
                $this->whatsappCalls($calls, $whatsapp);
            }

            if ($messages) {
                $whatsapp->messages()->createMany($messages);
            }

            WhatsappEvent::dispatch($whatsapp);

            return $whatsapp;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            if (isset($whatsapp->id)) {
                $whatsapp->delete();
            }

            return false;
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
            $whatsappCall = $whats->calls()->create($call['call']);

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
