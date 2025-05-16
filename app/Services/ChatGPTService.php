<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ChatGPTService
{
    /**
     * @var string
     */
    static private string $endPoint = 'https://api.openai.com/v1/completions';

    /**
     * @param $message
     * @return mixed
     * @throws GuzzleException
     */
    static public function sendMessage($message): mixed
    {
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('CHATGPT_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = [
            'prompt' => $message,
            'temperature' => 0.4,
            'max_tokens' => 500,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'model' => 'text-davinci-003'
        ];

        $response = $client->post(self::$endPoint, ['json' => $data]);
        $result = json_decode((string)$response->getBody(), true);
        return $result['choices'][0]['text'];
    }
}
