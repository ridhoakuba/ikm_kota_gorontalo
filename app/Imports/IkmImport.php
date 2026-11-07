<?php

namespace App\Imports;

use App\Models\DataIkm;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IkmImport implements ToModel, WithHeadingRow
{
    /**
     * Transformasi setiap baris Excel menjadi model DataIkm.
     *
     * Kolom Excel yang dibaca:
     *   - nib
     *   - nama_proyek                    → disimpan ke nama_usaha
     *   - nama_perusahaan                → disimpan ke nama_pemilik
     *   - alamat_usaha                   → disimpan ke alamat
     *   - kelurahan_usaha                → disimpan ke kelurahan_id
     *   - kecamatan_usaha                → disimpan ke kelurahans.kecamatan
     *   - kbli / no_kbli / kode_kbli     → disimpan ke kode_kbli (5 digit bersih)
     *   - judul_kbli                     → disimpan ke jenis_usaha
     */
    public function model(array $row): ?DataIkm
    {
        // Lewati baris jika kolom 'nib' kosong
        if (empty($row['nib'])) {
            return null;
        }

        // Bersihkan nilai kecamatan dari Excel
        $kecamatan = isset($row['kecamatan_usaha']) ? trim((string) $row['kecamatan_usaha']) : null;

        // 1. Cari atau buat Kelurahan berdasarkan nama kelurahan dari Excel.
        //    Jika dibuat baru, langsung isi kecamatan sekaligus.
        $kelurahan = Kelurahan::firstOrCreate(
            ['nama_kelurahan' => trim((string) ($row['kelurahan_usaha'] ?? ''))],
            ['kecamatan' => $kecamatan ?: null]
        );

        // Jika kelurahan sudah ada tapi kecamatannya masih kosong, update sekarang.
        if (empty($kelurahan->kecamatan) && !empty($kecamatan)) {
            $kelurahan->update(['kecamatan' => $kecamatan]);
        }

        // 2. Ambil & normalisasi kode_kbli (ambil 5 digit angka pertama, mis. 16221v2025 -> 16221)
        $rawKbli = trim((string) ($row['kbli'] ?? $row['no_kbli'] ?? $row['kode_kbli'] ?? $this->findKbliColumnValue($row) ?? ''));
        $judulKbli = trim((string) ($row['judul_kbli'] ?? $row['jenis_usaha'] ?? ''));

        // Jika kode_kbli dan judul_kbli tergabung dalam satu kolom (misal "10710 - Industri...")
        if ($rawKbli !== '' && $judulKbli === '' && preg_match('/^(\d{4,5}[a-zA-Z0-9]*)\s*[-:]\s*(.+)$/u', $rawKbli, $matches)) {
            $rawKbli = trim($matches[1]);
            $judulKbli = trim($matches[2]);
        }
        if ($judulKbli !== '' && preg_match('/^[\(\[]?(\d{4,5}[a-zA-Z0-9]*)[\)\]]?\s*(?:[-:]\s*|\s+)(.+)$/u', $judulKbli, $matches)) {
            if ($rawKbli === '') {
                $rawKbli = trim($matches[1]);
            }
            $judulKbli = trim($matches[2]);
        }

        $cleanKbli = self::sanitizeKodeKbli($rawKbli, $judulKbli);

        // 3. Cek apakah data dengan NIB ini sudah ada di database
        $nib = trim((string) $row['nib']);
        $existing = DataIkm::where('nib', $nib)->first();

        // 4. Tentukan status_verifikasi:
        //    - Jika data sudah ada → pertahankan status yang ada
        //    - Jika data baru → default 'menunggu'
        $statusVerifikasi = $existing
            ? $existing->status_verifikasi
            : DataIkm::STATUS_MENUNGGU;

        if ($cleanKbli === null && $existing && !empty($existing->kode_kbli)) {
            $cleanKbli = self::sanitizeKodeKbli((string) $existing->kode_kbli, $judulKbli);
        }

        // 5. Update atau buat data IKM menggunakan kunci 'nib'
        DataIkm::updateOrCreate(
            ['nib' => $nib],
            [
                'kelurahan_id'      => $kelurahan->id,
                'nama_usaha'        => trim((string) ($row['nama_proyek'] ?? '')),
                'nama_pemilik'      => trim((string) ($row['nama_perusahaan'] ?? '')),
                'alamat'            => trim((string) ($row['alamat_usaha'] ?? '')),
                'kode_kbli'         => $cleanKbli,
                'jenis_usaha'       => $judulKbli,
                'status_verifikasi' => $statusVerifikasi,
            ]
        );

        // Return null karena kita sudah handle insert/update secara manual
        // untuk menghindari duplikasi dari ToModel
        return null;
    }

    /**
     * Normalisasi string kode KBLI:
     * - Ambil 5 digit angka pertama (contoh: "16221v2025" -> "16221").
     * - Jika kosong, cari dari judul KBLI (termasuk "Industri Barang Bangunan Dari Kayu" -> "16221").
     */
    public static function sanitizeKodeKbli(?string $rawKbli, ?string $judulKbli = null): ?string
    {
        $rawKbli = trim((string) ($rawKbli ?? ''));
        $cleanKbli = null;

        if (preg_match('/(\d{5})/', $rawKbli, $matches)) {
            $cleanKbli = $matches[1];
        } elseif ($rawKbli !== '') {
            $cleanKbli = $rawKbli;
        }

        if ($cleanKbli === null && $judulKbli !== null && trim($judulKbli) !== '') {
            $cleanKbli = self::resolveKodeKbliFromJudul($judulKbli);
        }

        return $cleanKbli;
    }

    /**
     * Cari kolom di baris Excel yang mengandung kata 'kbli' (selain judul/uraian/nama).
     */
    private function findKbliColumnValue(array $row): mixed
    {
        foreach ($row as $key => $value) {
            $k = strtolower((string) $key);
            if (str_contains($k, 'kbli') && !str_contains($k, 'judul') && !str_contains($k, 'uraian') && !str_contains($k, 'nama')) {
                if (!is_null($value) && trim((string) $value) !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * Petakan Judul KBLI standar OSS/BPS ke Kode KBLI 5 digit jika kolom kode kosong.
     */
    public static function resolveKodeKbliFromJudul(string $judul): ?string
    {
        $normalized = strtolower(preg_replace('/\s+/', ' ', trim($judul)));

        $map = [
            'industri barang bangunan dari kayu' => '16221',
            'reparasi dan perawatan sepeda motor' => '45407',
            'industri air minum isi ulang' => '11052',
            'reparasi mobil' => '45201',
            'industri pakaian jadi (konveksi) dari tekstil' => '14111',
            'industri pakaian jadi (konfeksi) dari tekstil' => '14111',
            'industri pompa lainnya, kompresor, kran dan klep/katup' => '28130',
            'industri bumbu masak dan penyedap masakan' => '10772',
            'industri bumbu masakan lainnya' => '10772',
            'penjahitan dan pembuatan pakaian sesuai pesanan' => '14120',
            'industri penjahitan dan pembuatan (bukan konfeksi) pakaian sesuai pesanan/kustomisasi' => '14120',
            'industri produk masak lainnya' => '10779',
            'industri minuman ringan' => '11040',
            'industri minyak goreng kelapa' => '10422',
            'industri produk makanan lainnya' => '10799',
            'industri produk makanan lainnya ytdl' => '10799',
            'industri cokelat dan kembang gula dari cokelat' => '10732',
            'industri penggilingan logam bukan besi' => '24203',
            'industri furnitur dari bahan lainnya ytdl' => '31009',
            'industri furnitur lainnya' => '31009',
            'industri furnitur dari kayu' => '31001',
            'portal web dan/atau platform digital dengan tujuan komersial' => '63122',
            'portal web dan/atau platform digital tanpa tujuan komersial' => '63121',
            'industri mesin dan peralatan kantor lainnya' => '28179',
            'kegiatan jasa penunjang pencetakan' => '18120',
            'reparasi furnitur dan perlengkapan rumah' => '95240',
            'industri kain sulaman' => '13912',
            'industri pencetakan umum' => '18111',
            'pencetakan umum' => '18111',
            'industri produk roti dan kue' => '10710',
            'industri produk bakeri' => '10710',
            'industri gips' => '23952',
            'industri gula merah' => '10722',
            'reparasi alat-alat elektronik konsumen' => '95210',
            'reparasi dan pemeliharaan alat-alat elektronik konsumen' => '95210',
            'industri peralatan pengontrol dan pendistribusian listrik' => '27120',
            'industri kapal dan perahu' => '30111',
            'industri suku cadang dan aksesori kendaraan bermotor roda empat atau lebih' => '29300',
            'industri kembang gula lainnya' => '10733',
            'industri kerajinan ukiran dari kayu bukan furnitur' => '16292',
            'industri kerajinan ytdl' => '32909',
            'industri pengolahan lainnya ytdl' => '32909',
            'industri makanan dan masakan olahan' => '10750',
            'industri kerupuk, keripik, peyek dan sejenisnya' => '10794',
            'industri kerupuk, keripik, peyek, dan sejenisnya' => '10794',
            'industri tempe kedelai' => '10391',
            'industri tahu kedelai' => '10392',
            'industri makanan dari kedele dan kacang-kacangan lainnya bukan kecap, tempe dan tahu' => '10393',
            'industri kimia dasar organik yang bersumber dari hasil pertanian' => '20115',
            'reparasi komputer dan peralatan sejenisnya' => '95110',
            'reparasi dan pemeliharaan komputer dan peralatan sejenisnya' => '95110',
            'industri peralatan audio dan video elektronik lainnya' => '26490',
            'industri kue basah' => '10792',
            'industri pengolahan es sejenisnya yang dapat dimakan (bukan es batu dan es Balok)' => '10532',
            'industri pengolahan es sejenisnya yang dapat dimakan (bukan es batu dan es balok)' => '10532',
            'industri makaroni, mie dan produk sejenisnya' => '10740',
            'reparasi mesin untuk keperluan khusus' => '33122',
            'reparasi mesin untuk keperluan umum' => '33121',
            'industri logam dasar mulia' => '24201',
            'industri pembuatan logam dasar mulia' => '24201',
            'industri pembuatan logam dasar bukan besi' => '24202',
            'industri pengolahan kopi' => '10761',
            'industri pengolahan dan pengawetan ikan dan biota air (bukan udang) dalam kaleng' => '10213',
            'pengolahan dan pengawetan ikan dengan metode lainnya' => '10219',
            'industri minuman lainnya' => '11090',
            'produksi es' => '35302',
            'industri pengasinan buah-buahan dan sayuran' => '10311',
            'industri makanan sereal' => '10616',
            'industri mesin dan perkakas mesin untuk pengelasan yang menggunakan arus listrik' => '28224',
            'industri mesin dan perkakas mesin untuk pengerjaan logam' => '28221',
            'industri perkakas tangan yang digerakkan tenaga' => '28180',
            'industri mesin pendingin' => '28193',
            'industri mesin pengolahan makanan, minuman dan tembakau' => '28250',
            'industri peralatan penerangan lainnya' => '27409',
            'industri mesin untuk keperluan umum lainnya ytdl' => '28199',
            'industri pengolahan herbal (herb infusion)' => '10762',
            'industri minyak atsiri' => '20294',
            'industri produk masak dari kelapa' => '10773',
            'industri minyak goreng kelapa sawit' => '10437',
            'industri minyak mentah inti kelapa sawit (crude palm kernel oil)' => '10432',
            'industri mortar atau beton siap pakai' => '23957',
            'industri pakaian jadi (konfeksi) dari kulit dan tiruan kulit' => '14112',
            'industri pakaian jadi (konveksi) dari kulit' => '14112',
            'industri pakaian jadi sulaman/bordir' => '14132',
            'industri pencetakan kain' => '13134',
            'industri rajutan kaos kaki dan sejenisnya' => '14303',
            'industri pelumatan buah-buahan dan sayuran' => '10312',
            'industri pencetakan 3d printing' => '18113',
            'industri pencetakan khusus' => '18112',
            'industri pengeringan buah-buahan dan sayuran' => '10313',
            'industri pengolahan dan pengawetan lainnya buah-buahan dan sayuran bukan kacang-kacangan' => '10319',
            'industri pengolahan dan pengawetan lainnya untuk biota air lainnya' => '10299',
            'industri pengolahan dan pengawetan produk daging dan daging unggas' => '10130',
            'pengolahan dan pengawetan daging dan produk daging' => '10130',
            'industri pengolahan es krim' => '10531',
            'industri pengolahan susu segar dan krim' => '10510',
            'industri pengolahan produk dari susu lainnya' => '10590',
            'industri pengolahan sari buah dan sayuran' => '10330',
            'industri peragian/fermentasi ikan' => '10215',
            'reparasi peralatan komunikasi' => '95120',
            'reparasi dan pemeliharaan peralatan komunikasi' => '95120',
            'industri peralatan listrik lainnya' => '27900',
            'industri produk logam struktural bukan aluminium untuk konstruksi ringan' => '25119',
            'industri produk obat tradisional untuk manusia' => '21022',
            'industri pupuk alam/non sintetis hara makro primer' => '20121',
            'industri ransum pakan hewan' => '10801',
            'industri sabun dan bahan pembersih keperluan rumah tangga' => '20231',
            'industri sepeda motor roda dua dan tiga' => '30911',
            'industri tali' => '13941',
            'industri tepung campuran dan adonan tepung' => '10615',
            'jasa industri untuk berbagai pengerjaan khusus logam dan barang dari logam' => '25920',
            'jasa kalibrasi/metrologi' => '71203',
            'pemulihan material barang logam' => '38301',
            'penelitian dan pengembangan ilmu pengetahuan alam dan teknologi rekayasa lainnya' => '72102',
            'penerbitan piranti lunak (software)' => '58200',
            'penerbitan surat kabar, jurnal dan buletin atau majalah' => '58130',
            'pengolahan dan pengawetan ikan menjadi produk olahan' => '10216',
            'perdagangan eceran bahan kimia' => '47736',
            'reparasi peralatan rumah tangga dan peralatan rumah dan kebun' => '95220',
            'reparasi dan pemeliharaan peralatan rumah tangga dan peralatan rumah dan kebun' => '95220',
            'reparasi barang rumah tangga dan pribadi lainnya' => '95299',
            'reparasi dan pemeliharaan kapal, perahu, dan bangunan terapung' => '33151',
            'reparasi kapal, perahu dan bangunan terapung' => '33151',
            'reparasi dan pemeliharaan produk logam pabrikasi lainnya' => '33119',
            'reparasi produk logam pabrikasi lainnya' => '33119',
            'reparasi motor listrik, generator dan transformator' => '33141',
            'reparasi peralatan lainnya' => '33190',
            'reparasi peralatan listrik lainnya' => '33149',
            'reproduksi media rekaman suara dan piranti lunak' => '18201',
        ];

        return $map[$normalized] ?? null;
    }
}
