<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paket extends Model
{
    use HasFactory;

    /**
     * Nama tabel terkait.
     *
     * @var string
     */
    protected $table = 'paket';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'no_resi',
        'pengirim',
        'penerima',
        'alamat_tujuan',
        'latitude',
        'longitude',
        'status',
        'kurir_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    /**
     * Relasi ke Kurir.
     */
    public function kurir(): BelongsTo
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }
}

