<?php

namespace App\Exports;

use App\Models\DataIkm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanIkmExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Ambil semua data IKM berstatus tidak_ditemukan.
     */
    public function collection()
    {
        return DataIkm::with('kelurahan')
            ->where('status_verifikasi', DataIkm::STATUS_TIDAK_DITEMUKAN)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIB',
            'Nama Usaha',
            'Nama Pemilik',
            'Kelurahan',
            'Kecamatan',
            'Jenis Usaha',
            'Status Verifikasi',
            'Catatan Petugas',
            'Sudah Ditindaklanjuti',
            'Terakhir Diperbarui',
        ];
    }

    public function map($ikm): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $ikm->nib ?? '-',
            $ikm->nama_usaha ?? '-',
            $ikm->nama_pemilik ?? '-',
            $ikm->kelurahan?->nama_kelurahan ?? '-',
            $ikm->kelurahan?->kecamatan ?? '-',
            $ikm->jenis_usaha ?? '-',
            'Tidak Ditemukan',
            $ikm->catatan_petugas ?? '-',
            $ikm->ditindaklanjuti ? 'Ya' : 'Belum',
            $ikm->updated_at?->format('d/m/Y H:i') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFEF4444']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
