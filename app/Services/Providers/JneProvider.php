<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;

class JneProvider implements ProviderInterface
{
    public function estimate(int $weightGrams, array $options = []): ?array
    {
        $apiUrl = env('JNE_API_URL');
        $apiKey = env('JNE_API_KEY');

        if (!$apiUrl || !$apiKey) {
            return null;
        }

        try {
            $payload = [
                'origin' => $options['origin'] ?? null,
                'destination' => $options['destination'] ?? null,
                'weight' => $weightGrams,
                'service' => $options['service'] ?? 'regular'
            ];

            $resp = Http::withToken($apiKey)->acceptJson()->post($apiUrl, $payload);
            if ($resp->successful()) {
                $json = $resp->json();
                if (isset($json['cost'])) {
                    return ['cost' => (int) $json['cost'], 'eta' => $json['eta'] ?? 'Estimasi tidak tersedia'];
                }
                if (isset($json['results'][0]['cost'])) {
                    return ['cost' => (int) $json['results'][0]['cost'], 'eta' => $json['results'][0]['eta'] ?? 'Estimasi tidak tersedia'];
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('JNE provider call failed: ' . $e->getMessage());
        }

        return null;
    }
}
