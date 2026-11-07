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
        'kode_kbli',
        'jenis_usaha',
        'status_verifikasi',
        'catatan_petugas',
        'ditindaklanjuti',
        'data_verifikasi',
        'verified_at',
        'verified_by',
        'sertifikat_halal',
        'izin_bpom',
        'pirt',
        'updated_by_role',
        'last_verified_by',
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
     * Role pihak yang terakhir memperbarui data (riwayat audit).
     */
    const UPDATED_BY_KELURAHAN = 'kelurahan';
    const UPDATED_BY_INDUSTRI  = 'industri';

    /**
     * Pilihan filter legalitas pada Master Data IKM.
     */
    const FILTER_LEGALITAS = [
        'belum_halal' => 'Belum Ada Halal',
        'sudah_halal' => 'Sudah Ada Halal',
        'belum_bpom'  => 'Belum Ada BPOM',
        'sudah_bpom'  => 'Sudah Ada BPOM',
    ];

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
     * User (Kelurahan / Industri) yang terakhir memperbarui data verifikasi.
     */
    public function lastVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_verified_by');
    }

    /**
     * Scope: saring berdasarkan status legalitas (Halal / BPOM).
     * Nilai yang diterima: belum_halal, sudah_halal, belum_bpom, sudah_bpom.
     */
    public function scopeFilterLegalitas($query, ?string $filter)
    {
        $kolom = match ($filter) {
            'belum_halal', 'sudah_halal' => 'sertifikat_halal',
            'belum_bpom', 'sudah_bpom'   => 'izin_bpom',
            default                      => null,
        };

        if ($kolom === null) {
            return $query;
        }

        if (str_starts_with($filter, 'sudah_')) {
            return $query->whereNotNull($kolom)->where($kolom, '!=', '');
        }

        return $query->where(fn ($q) => $q->whereNull($kolom)->orWhere($kolom, ''));
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
