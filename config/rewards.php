<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Reward configuration
    |--------------------------------------------------------------------------
    |
    | `points_per_currency`: how many currency units (Rp) are required to earn
    | 1 point. Example: 10000 => 1 point per Rp10.000.
    |
    | `minimum_points`: minimum points awarded per eligible order (default 0).
    |
    */

    'points_per_currency' => env('REWARD_POINTS_PER', 10000),
    'minimum_points' => env('REWARD_MIN_POINTS', 0),
];
