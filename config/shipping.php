<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping Zones Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi zona pengiriman berdasarkan provinsi di Indonesia
    | dengan tarif per kg untuk setiap kurir
    |
    */

    'zones' => [
        'zona_1' => [
            'name' => 'Jakarta & Sekitarnya',
            'provinces' => [
                'DKI Jakarta',
                'Banten',
                'Jawa Barat'
            ],
            'rates' => [
                'jne' => ['base' => 10000, 'per_kg' => 3000],
                'jnt' => ['base' => 9000, 'per_kg' => 2500],
                'ninja' => ['base' => 12000, 'per_kg' => 4000],
                'antera' => ['base' => 8000, 'per_kg' => 2000]
            ]
        ],
        'zona_2' => [
            'name' => 'Jawa Tengah & Timur',
            'provinces' => [
                'Jawa Tengah',
                'DI Yogyakarta',
                'Jawa Timur'
            ],
            'rates' => [
                'jne' => ['base' => 15000, 'per_kg' => 4000],
                'jnt' => ['base' => 14000, 'per_kg' => 3500],
                'ninja' => ['base' => 18000, 'per_kg' => 5000],
                'antera' => ['base' => 12000, 'per_kg' => 3000]
            ]
        ],
        'zona_3' => [
            'name' => 'Sumatera',
            'provinces' => [
                'Aceh',
                'Sumatera Utara',
                'Sumatera Barat',
                'Riau',
                'Kepulauan Riau',
                'Jambi',
                'Sumatera Selatan',
                'Bangka Belitung',
                'Bengkulu',
                'Lampung'
            ],
            'rates' => [
                'jne' => ['base' => 25000, 'per_kg' => 6000],
                'jnt' => ['base' => 23000, 'per_kg' => 5500],
                'ninja' => ['base' => 30000, 'per_kg' => 7000],
                'antera' => ['base' => 20000, 'per_kg' => 5000]
            ]
        ],
        'zona_4' => [
            'name' => 'Kalimantan',
            'provinces' => [
                'Kalimantan Barat',
                'Kalimantan Tengah',
                'Kalimantan Selatan',
                'Kalimantan Timur',
                'Kalimantan Utara'
            ],
            'rates' => [
                'jne' => ['base' => 30000, 'per_kg' => 7000],
                'jnt' => ['base' => 28000, 'per_kg' => 6500],
                'ninja' => ['base' => 35000, 'per_kg' => 8000],
                'antera' => ['base' => 25000, 'per_kg' => 6000]
            ]
        ],
        'zona_5' => [
            'name' => 'Sulawesi',
            'provinces' => [
                'Sulawesi Utara',
                'Sulawesi Tengah',
                'Sulawesi Selatan',
                'Sulawesi Tenggara',
                'Sulawesi Barat',
                'Gorontalo'
            ],
            'rates' => [
                'jne' => ['base' => 35000, 'per_kg' => 8000],
                'jnt' => ['base' => 32000, 'per_kg' => 7500],
                'ninja' => ['base' => 40000, 'per_kg' => 9000],
                'antera' => ['base' => 28000, 'per_kg' => 7000]
            ]
        ],
        'zona_6' => [
            'name' => 'Bali, NTB, NTT',
            'provinces' => [
                'Bali',
                'Nusa Tenggara Barat',
                'Nusa Tenggara Timur'
            ],
            'rates' => [
                'jne' => ['base' => 30000, 'per_kg' => 7000],
                'jnt' => ['base' => 28000, 'per_kg' => 6500],
                'ninja' => ['base' => 35000, 'per_kg' => 8000],
                'antera' => ['base' => 25000, 'per_kg' => 6000]
            ]
        ],
        'zona_7' => [
            'name' => 'Maluku & Papua',
            'provinces' => [
                'Maluku',
                'Maluku Utara',
                'Papua',
                'Papua Barat',
                'Papua Tengah',
                'Papua Pegunungan',
                'Papua Selatan',
                'Papua Barat Daya'
            ],
            'rates' => [
                'jne' => ['base' => 50000, 'per_kg' => 12000],
                'jnt' => ['base' => 45000, 'per_kg' => 11000],
                'ninja' => ['base' => 60000, 'per_kg' => 14000],
                'antera' => ['base' => 40000, 'per_kg' => 10000]
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Free Shipping Minimum
    |--------------------------------------------------------------------------
    |
    | Minimum pembelian untuk mendapatkan gratis ongkir
    |
    */
    'free_shipping_minimum' => 300000,

    /*
    |--------------------------------------------------------------------------
    | Default Weight
    |--------------------------------------------------------------------------
    |
    | Berat default jika buku tidak memiliki berat (dalam gram)
    |
    */
    'default_book_weight' => 500,
];
