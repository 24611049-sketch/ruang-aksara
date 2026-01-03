<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;

class NinjaProvider implements ProviderInterface
{
    public function estimate(int $weightGrams, array $options = []): ?array
    {
        $apiUrl = env('NINJA_API_URL');
        $apiKey = env('NINJA_API_KEY');

        if (!$apiUrl || !$apiKey) return null;

        try {
            $payload = [
                'from' => $options['origin'] ?? null,
                'to' => $options['destination'] ?? null,
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
            \Log::warning('Ninja provider call failed: ' . $e->getMessage());
        }

        return null;
    }
}
