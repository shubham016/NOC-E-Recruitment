<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SparrowSmsService
{
    protected string $token;
    protected string $from;
    protected string $baseUrl;

    public function __construct()
    {
        $this->token   = config('services.sparrowsms.token', '');
        $this->from    = config('services.sparrowsms.from', '');
        $this->baseUrl = rtrim(config('services.sparrowsms.base_url', 'http://api.sparrowsms.com/v2'), '/');
    }

    public function send(string $to, string $text): array
    {
        try {
            $response = Http::asForm()->post("{$this->baseUrl}/sms/", [
                'token' => $this->token,
                'from'  => $this->from,
                'to'    => $to,
                'text'  => $text,
            ]);

            $data = $response->json();

            Log::info('Sparrow SMS sent', [
                'to'            => $to,
                'response_code' => $data['response_code'] ?? null,
                'response'      => $data['response'] ?? null,
            ]);

            return $data ?? ['response_code' => 0, 'response' => 'Empty response'];
        } catch (\Exception $e) {
            Log::error('Sparrow SMS failed', [
                'to'    => $to,
                'error' => $e->getMessage(),
            ]);

            return [
                'response_code' => 0,
                'response'      => $e->getMessage(),
            ];
        }
    }

    public function getCredits(): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/credit/", [
                'token' => $this->token,
            ]);

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Sparrow SMS credit check failed', ['error' => $e->getMessage()]);
            return ['credits_available' => 0, 'response_code' => 0];
        }
    }
}
