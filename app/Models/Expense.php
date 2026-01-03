<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $receipt
 */
class Expense extends Model
{
    protected $fillable = [
        'category',
        'amount',
        'description',
        'expense_date',
        'receipt'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Kategori yang tersedia
    public static function categories()
    {
        return [
            'gaji' => 'Gaji Karyawan',
            'sewa' => 'Sewa Tempat',
            'listrik' => 'Listrik & Air',
            'internet' => 'Internet & Telepon',
            'marketing' => 'Marketing & Promosi',
            'pengiriman' => 'Biaya Pengiriman',
            'perlengkapan' => 'Perlengkapan Kantor',
            'maintenance' => 'Perawatan & Perbaikan',
            'lain-lain' => 'Lain-lain'
        ];
    }
}
