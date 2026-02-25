<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function createCompletion($prompt)
    {
        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-4',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.7,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            return [
                'choices' => [
                    [
                        'text' => $responseData['choices'][0]['message']['content']
                    ]
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('فشل في الاتصال بخدمة OpenAI: ' . $e->getMessage());
        }
    }
}
