<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;

class AnteraProvider implements ProviderInterface
{
    public function estimate(int $weightGrams, array $options = []): ?array
    {
        $apiUrl = env('ANTERA_API_URL');
        $apiKey = env('ANTERA_API_KEY');

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
                if (isset($json['cost'])) {
                    return ['cost' => (int)$json['cost'], 'eta' => $json['eta'] ?? 'Estimasi tidak tersedia'];
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Antera provider call failed: ' . $e->getMessage());
        }

        return null;
    }
}
