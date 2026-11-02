<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumenVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'instrumen_verifikasis';

    protected $fillable = [
        'nama_instrumen',
        'deskripsi',
        'tipe',
        'is_wajib',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'is_wajib' => 'boolean',
        'aktif'    => 'boolean',
        'urutan'   => 'integer',
    ];

    /**
     * Scope: hanya instrumen yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
