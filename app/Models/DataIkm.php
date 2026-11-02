<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataIkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'nib',
        'kelurahan_id',
        'nama_usaha',
        'nama_pemilik',
        'alamat',
        'jenis_usaha',
        'status_verifikasi',
        'catatan_petugas',
        'ditindaklanjuti',
        'data_verifikasi',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'status_verifikasi' => 'string',
        'ditindaklanjuti'   => 'boolean',
        'data_verifikasi'   => 'array',
        'verified_at'       => 'datetime',
    ];

    /**
     * Status verifikasi yang tersedia.
     */
    const STATUS_MENUNGGU        = 'menunggu';
    const STATUS_DITEMUKAN       = 'ditemukan';
    const STATUS_TIDAK_DITEMUKAN = 'tidak_ditemukan';

    /**
     * DataIkm dimiliki oleh satu Kelurahan.
     */
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * User (Petugas Kelurahan) yang melakukan verifikasi.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Helper untuk mengambil nilai jawaban verifikasi berdasarkan slug instrumen.
     */
    public function getJawabanVerifikasi(string $slug): mixed
    {
        if (!is_array($this->data_verifikasi) || !isset($this->data_verifikasi[$slug])) {
            return null;
        }

        $val = $this->data_verifikasi[$slug];
        return is_array($val) ? ($val['nilai'] ?? null) : $val;
    }
}
