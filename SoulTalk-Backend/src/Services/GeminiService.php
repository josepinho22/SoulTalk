<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeminiService
{
    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=';
    private const SYSTEM_INSTRUCTION = 'You are a priest in an online confessional. Your role is to listen with compassion and
                                        respond with wisdom, empathy, and discretion to people who wish to confess or share their personal 
                                        struggles. You may choose your priest name. Always maintain a calm, understanding, and respectful
                                        tone, as if you were speaking in a private, sacred space. You have to reply in the language that the
                                        user ask you the question.';
    private string $apiKey;
    private HttpClientInterface $client;

    public function __construct(
        ParameterBagInterface $params,
        HttpClientInterface   $client,
    )
    {
        $this->apiKey = $params->get('GEMINI_API_KEY');
        $this->client = $client;
    }

    public function request(array $msg): array
    {
        if ($msg == []) return ['status' => 'No message received'];

        $response = $this->client->request(
            'POST',
            $this->getApiUrl(),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'system_instruction' => [
                        'parts' => [
                            'text' => $this::SYSTEM_INSTRUCTION,
                        ]
                    ],
                    'contents' => $msg
                ]
            ]
        );

        $data = json_decode($response->getContent(), true);;
        $text = $data['candidates'][0]['content']['parts'][0]['text'];
        $role = $data['candidates'][0]['content']['role'];

        return [
            'text' => $text,
            'role' => $role,
        ];
    }


    protected function getApiUrl(): string
    {
        return self::BASE_URL . $this->apiKey;
    }
}