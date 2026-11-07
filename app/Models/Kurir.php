<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurir extends Model
{
    use HasFactory;

    /**
     * Nama tabel terkait.
     *
     * @var string
     */
    protected $table = 'kurir';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'telepon',
    ];

    /**
     * Relasi ke Paket (1 kurir menangani banyak paket).
     */
    public function paket(): HasMany
    {
        return $this->hasMany(Paket::class, 'kurir_id');
    }
}

