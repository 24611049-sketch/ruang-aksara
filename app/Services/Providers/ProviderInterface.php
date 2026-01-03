<?php

namespace App\Services\Providers;

interface ProviderInterface
{
    /**
     * Estimate shipping cost and eta.
     * Return ['cost' => int, 'eta' => string] or null if provider can't handle.
     */
    public function estimate(int $weightGrams, array $options = []): ?array;
}
