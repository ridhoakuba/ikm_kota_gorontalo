<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'form_verifikasis';

    protected $fillable = [
        'nama_instrumen',
        'slug',
        'deskripsi',
        'tipe_input',
        'opsi_pilihan',
        'is_required',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'opsi_pilihan' => 'array',
        'is_required'  => 'boolean',
        'is_active'    => 'boolean',
        'urutan'       => 'integer',
    ];

    /**
     * Scope: hanya instrumen yang aktif, diurutkan berdasarkan urutan.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }

    /**
     * Generate slug unik dari nama instrumen jika belum diisi.
     */
    public static function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($nama, '_');
        if (empty($baseSlug)) {
            $baseSlug = 'instrumen_' . time();
        }

        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}_{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Seed instrumen verifikasi standar awal jika tabel kosong.
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            [
                'nama_instrumen' => 'Upload Foto Tampak Depan / Plang Usaha',
                'slug'           => 'foto_tempat_usaha',
                'deskripsi'      => 'Foto bukti fisik bangunan tempat usaha atau plang nama usaha di lokasi',
                'tipe_input'     => 'file',
                'opsi_pilihan'   => null,
                'is_required'    => true,
                'urutan'         => 1,
                'is_active'      => true,
            ],
            [
                'nama_instrumen' => 'Titik Koordinat Lokasi Usaha (GPS)',
                'slug'           => 'koordinat_lokasi',
                'deskripsi'      => 'Koordinat lintang (latitude) & bujur (longitude) hasil pengecekan GPS',
                'tipe_input'     => 'koordinat',
                'opsi_pilihan'   => null,
                'is_required'    => true,
                'urutan'         => 2,
                'is_active'      => true,
            ],
            [
                'nama_instrumen' => 'Status Operasional Usaha',
                'slug'           => 'status_operasional',
                'deskripsi'      => 'Kondisi operasional usaha saat didatangi petugas kelurahan',
                'tipe_input'     => 'select',
                'opsi_pilihan'   => ['Aktif Berproduksi', 'Tutup Sementara', 'Pindah Alamat / Lokasi', 'Tidak Beroperasi / Bangkrut'],
                'is_required'    => true,
                'urutan'         => 3,
                'is_active'      => true,
            ],
            [
                'nama_instrumen' => 'Upload Foto KTP Pemilik',
                'slug'           => 'foto_ktp',
                'deskripsi'      => 'Foto identitas pemilik jika dapat ditemui di tempat usaha',
                'tipe_input'     => 'file',
                'opsi_pilihan'   => null,
                'is_required'    => false,
                'urutan'         => 4,
                'is_active'      => true,
            ],
            [
                'nama_instrumen' => 'Catatan Temuan Lapangan',
                'slug'           => 'catatan_lapangan',
                'deskripsi'      => 'Keterangan tambahan mengenai hasil pemeriksaan di lapangan',
                'tipe_input'     => 'text',
                'opsi_pilihan'   => null,
                'is_required'    => false,
                'urutan'         => 5,
                'is_active'      => true,
            ],
        ];

        foreach ($defaults as $item) {
            static::firstOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
