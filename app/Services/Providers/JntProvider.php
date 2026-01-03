<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;

class JntProvider implements ProviderInterface
{
    public function estimate(int $weightGrams, array $options = []): ?array
    {
        $apiUrl = env('JNT_API_URL');
        $apiKey = env('JNT_API_KEY');

        if (!$apiUrl || !$apiKey) return null;

        try {
            $payload = [
                'origin' => $options['origin'] ?? null,
                'destination' => $options['destination'] ?? null,
                'weight' => $weightGrams,
            ];

            $resp = Http::withToken($apiKey)->acceptJson()->post($apiUrl, $payload);
            if ($resp->successful()) {
                $json = $resp->json();
                if (isset($json['price'])) {
                    return ['cost' => (int)$json['price'], 'eta' => $json['eta'] ?? 'Estimasi tidak tersedia'];
                }
                if (isset($json['results'][0]['price'])) {
                    return ['cost' => (int)$json['results'][0]['price'], 'eta' => $json['results'][0]['eta'] ?? 'Estimasi tidak tersedia'];
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('JNT provider call failed: ' . $e->getMessage());
        }

        return null;
    }
}
