<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 03/2024
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\FileLayouts\Whatsapp;

use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Models\Files\Whatsapp\Whatsapp;
use App\Models\Files\Whatsapp\WhatsappCall;
use App\Models\Files\Whatsapp\WhatsappCallEvent;
use App\Models\Files\Whatsapp\WhatsappCallEventParticipant;
use App\Services\Socialmedias\WhatsappIpService;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Layout HTML arquivo de bilhetagem Whatsapp.
 */
class WhatsappHtmlTicketLayout extends HtmlCaseDocumentAbstract
{

    protected string $html;

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

    /**
     * @throws FileNotFoundException
     */
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


    /**
     * @return Collection
     */
    public function messages(): Collection
    {
        try {
            $patterns = [
                'timestamp' => '/<div class="t i">Timestamp<div class="m"><div>(.*?)<div class="p">/',
                'message_id' => '/<div class="t i">Message Id<div class="m"><div>(.*?)<div class="p">/',
                'sender' => '/<div class="t i">Sender<div class="m"><div>(.*?)<div class="p">/',
                'recipients' => '/<div class="t i">Recipients<div class="m"><div>(.*?)<div class="p">/',
                'group_id' => '/<div class="t i">Group Id<div class="m"><div>(.*?)<div class="p">/',
                'sender_ip' => '/<div class="t i">Sender Ip<div class="m"><div>(.*?)<div class="p">/',
                'sender_port' => '/<div class="t i">Sender Port<div class="m"><div>(.*?)<div class="p">/',
                'sender_device' => '/<div class="t i">Sender Device<div class="m"><div>(.*?)<div class="p">/',
                'type' => '/<div class="t i">Type<div class="m"><div>(.*?)<div class="p">/',
                'message_style' => '/<div class="t i">Message Style<div class="m"><div>(.*?)<div class="p">/',
                'message_size' => '/<div class="t i">Message Size<div class="m"><div>(.*?)<div class="p">/',
            ];

            $lines = explode('</div>', $this->html);
            $lines = array_filter($lines);
            $messages = [];
            $currentMessage = [];

            foreach ($lines as $line) {
                foreach ($patterns as $key => $pattern) {
                    if (preg_match($pattern, $line, $match)) {
                        $currentMessage[$key] = $match[1];
                        break;
                    }
                }

                if (isset($currentMessage['message_size'])) {
                    $messages[] = $currentMessage;
                    $currentMessage = [];
                }
            }

            return collect($messages);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
        }
    }


    public function calls(): Collection
    {
        try {
            return $this->extractCallLogs();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
        }
    }

    public function extractCallLogs(): Collection
    {
        try {
            $patterns = [
                'call_id' => '/<div class="t i">Call Id<div class="m"><div>(.*?)<div class="p">/',
                'call_creator' => '/<div class="t i">Call Creator<div class="m"><div>(.*?)<div class="p">/',
                'event_type' => '/<div class="t i">Type<div class="m"><div>(.*?)<div class="p">/',
                'event_timestamp' => '/<div class="t i">Timestamp<div class="m"><div>(.*?)<div class="p">/',
                'event_from' => '/<div class="t i">From<div class="m"><div>(.*?)<div class="p">/',
                'event_to' => '/<div class="t i">To<div class="m"><div>(.*?)<div class="p">/',
                'event_from_ip' => '/<div class="t i">From Ip<div class="m"><div>(.*?)<div class="p">/',
                'event_from_port' => '/<div class="t i">From Port<div class="m"><div>(.*?)<div class="p">/',
                'event_media_type' => '/<div class="t i">Media Type<div class="m"><div>(.*?)<div class="p">/',
                'participant_phone_number' => '/<div class="t i">Phone Number<div class="m"><div>(.*?)<div class="p">/',
                'participant_state' => '/<div class="t i">State<div class="m"><div>(.*?)<div class="p">/',
                'participant_platform' => '/<div class="t i">Platform<div class="m"><div>(.*?)<div class="p">/'
            ];

            $lines = explode('</div>', $this->html);
            $callLogs = [];
            $currentCall = null;
            $currentEvent = null;

            foreach ($lines as $line) {
                if (preg_match($patterns['call_id'], $line, $matches)) {
                    if ($currentCall) {
                        if ($currentEvent) {
                            $currentCall['events'][] = $currentEvent;
                        }
                        $callLogs[] = $currentCall;
                    }
                    $currentCall = [
                        'call_id' => $matches[1],
                        'call_creator' => '',
                        'events' => []
                    ];
                    $currentEvent = null;
                } elseif (preg_match($patterns['call_creator'], $line, $matches)) {
                    if ($currentCall) {
                        $currentCall['call_creator'] = $matches[1];
                    }
                } elseif (preg_match($patterns['event_type'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentCall['events'][] = $currentEvent;
                    }
                    $currentEvent = [
                        'type' => $matches[1],
                        'timestamp' => null,
                        'from' => '',
                        'to' => '',
                        'from_ip' => '',
                        'from_port' => '',
                        'media_type' => '',
                        'participants' => []
                    ];
                } elseif (preg_match($patterns['event_timestamp'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentEvent['timestamp'] = !empty($matches[1]) ? $matches[1] : null;
                    }
                } elseif (preg_match($patterns['event_from'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentEvent['from'] = $matches[1];
                    }
                } elseif (preg_match($patterns['event_to'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentEvent['to'] = $matches[1];
                    }
                } elseif (preg_match($patterns['event_from_ip'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentEvent['from_ip'] = $matches[1];
                    }
                } elseif (preg_match($patterns['event_from_port'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentEvent['from_port'] = $matches[1];
                    }
                } elseif (preg_match($patterns['event_media_type'], $line, $matches)) {
                    if ($currentEvent) {
                        $currentEvent['media_type'] = $matches[1];
                    }
                } elseif (preg_match($patterns['participant_phone_number'], $line, $matches)) {
                    $currentParticipant = [
                        'phone_number' => $matches[1],
                        'state' => '',
                        'platform' => ''
                    ];
                    if ($currentEvent) {
                        $currentEvent['participants'][] = $currentParticipant;
                    }
                } elseif (preg_match($patterns['participant_state'], $line, $matches)) {
                    if ($currentEvent && !empty($currentEvent['participants'])) {
                        $currentEvent['participants'][count($currentEvent['participants']) - 1]['state'] = $matches[1];
                    }
                } elseif (preg_match($patterns['participant_platform'], $line, $matches)) {
                    if ($currentEvent && !empty($currentEvent['participants'])) {
                        $currentEvent['participants'][count($currentEvent['participants']) - 1]['platform'] = $matches[1];
                    }
                }
            }

            if ($currentCall) {
                if ($currentEvent) {
                    $currentCall['events'][] = $currentEvent;
                }
                $callLogs[] = $currentCall;
            }
            unset($callLogs[0]);
            return collect($callLogs);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return collect();
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
            $messages = $this->messages();
            $calls = $this->calls();

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
    private
    function whatsappCalls(array $calls, Whatsapp $whats): void
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
    private
    function events(array $events, WhatsappCall $whatsappCall): void
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
    private
    function participants(array $participants, WhatsappCallEvent $callEvent): void
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
