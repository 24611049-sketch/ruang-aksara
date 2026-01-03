<?php

namespace App\Services;

class ShippingCalculator
{
    /**
     * Calculate shipping cost based on weight, province, and courier
     *
     * @param int $weightGrams - Total weight in grams
     * @param string $province - Province name
     * @param string $courier - Courier code (jne, jnt, ninja, antera)
     * @return array ['cost' => int, 'eta' => string, 'zone' => string]
     */
    public static function calculate(int $weightGrams, string $province, string $courier): array
    {
        // Get zone configuration
        $zones = config('shipping.zones');
        $zone = self::getZoneByProvince($province, $zones);
        
        if (!$zone) {
            // Default fallback
            return [
                'cost' => 15000,
                'eta' => '3-7 hari',
                'zone' => 'unknown',
                'zone_name' => 'Wilayah Tidak Dikenali'
            ];
        }

        // Get courier rates for this zone
        $courier = strtolower($courier);
        $rates = $zone['rates'][$courier] ?? null;
        
        if (!$rates) {
            return [
                'cost' => 15000,
                'eta' => '3-7 hari',
                'zone' => $zone['code'],
                'zone_name' => $zone['name']
            ];
        }

        // Calculate cost: base + (per_kg * weight_in_kg)
        $weightKg = ceil($weightGrams / 1000);
        $cost = $rates['base'] + ($rates['per_kg'] * max(0, $weightKg - 1));

        // ETA based on courier
        $etaMap = [
            'jne' => '2-5 hari',
            'jnt' => '2-4 hari',
            'ninja' => '1-3 hari',
            'antera' => '3-6 hari'
        ];

        return [
            'cost' => (int) $cost,
            'eta' => $etaMap[$courier] ?? '3-7 hari',
            'zone' => $zone['code'],
            'zone_name' => $zone['name']
        ];
    }

    /**
     * Get zone information by province name
     *
     * @param string $province
     * @param array $zones
     * @return array|null
     */
    private static function getZoneByProvince(string $province, array $zones): ?array
    {
        foreach ($zones as $zoneCode => $zoneData) {
            foreach ($zoneData['provinces'] as $prov) {
                if (stripos($province, $prov) !== false || stripos($prov, $province) !== false) {
                    return [
                        'code' => $zoneCode,
                        'name' => $zoneData['name'],
                        'rates' => $zoneData['rates']
                    ];
                }
            }
        }
        
        return null;
    }

    /**
     * Check if order qualifies for free shipping
     *
     * @param int $subtotal
     * @return bool
     */
    public static function isFreeShipping(int $subtotal): bool
    {
        $minimum = config('shipping.free_shipping_minimum', 300000);
        return $subtotal >= $minimum;
    }

    /**
     * Get all provinces grouped by zone
     *
     * @return array
     */
    public static function getProvinces(): array
    {
        $zones = config('shipping.zones');
        $provinces = [];
        
        foreach ($zones as $zoneCode => $zoneData) {
            foreach ($zoneData['provinces'] as $province) {
                $provinces[] = [
                    'name' => $province,
                    'zone' => $zoneData['name']
                ];
            }
        }
        
        sort($provinces);
        return $provinces;
    }

    /**
     * Legacy estimate method for backward compatibility
     *
     * @param string $method
     * @param int $weightGrams
     * @param array $options
     * @return array
     */
    public static function estimate(string $method, int $weightGrams, array $options = []): array
    {
        $province = $options['province'] ?? 'DKI Jakarta';
        $result = self::calculate($weightGrams, $province, $method);
        
        return [
            'cost' => $result['cost'],
            'eta' => $result['eta']
        ];
    }
}

