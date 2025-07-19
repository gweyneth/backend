<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * PengeluaranExport Class
 *
 * Kelas ini bertanggung jawab untuk mengekspor data pengeluaran ke file Excel.
 * Mengimplementasikan beberapa interface dari Maatwebsite/Excel untuk fungsionalitas
 * seperti query data, pemetaan, styling, dan pemformatan kolom.
 */
class PengeluaranExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    /**
     * Menyimpan filter yang diterapkan dari request.
     *
     * @var array
     */
    protected array $filters;

    /**
     * Counter untuk penomoran baris, diinisialisasi sebagai properti kelas.
     *
     * @var int
     */
    private int $rowNumber = 0;

    /**
     * @param array $filters Filter yang akan diterapkan pada query.
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Metode ini membangun query ke database berdasarkan filter yang diberikan.
     * Penggunaan metode `when()` membuat kode lebih ringkas dan deklaratif.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Memulai query dengan eager loading relasi 'karyawan' dan mengurutkan berdasarkan data terbaru.
        return Pengeluaran::with('karyawan')
            ->latest()
            ->when($this->filters['search_query'] ?? null, function ($query, $search) {
                // Menerapkan filter pencarian pada kolom 'keterangan'.
                $query->where('keterangan', 'like', "%{$search}%");
            })
            ->when($this->filters['jenis_pengeluaran'] ?? null, function ($query, $jenis) {
                // Menerapkan filter berdasarkan jenis pengeluaran.
                $query->where('jenis_pengeluaran', $jenis);
            })
            ->when($this->filters['start_date'] ?? null, function ($query, $startDate) {
                // Menerapkan filter tanggal mulai.
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($this->filters['end_date'] ?? null, function ($query, $endDate) {
                // Menerapkan filter tanggal selesai.
                $query->whereDate('created_at', '<=', $endDate);
            });
    }

    /**
     * Mendefinisikan judul untuk setiap kolom di file Excel.
     * Urutan diubah agar lebih logis untuk laporan (Tanggal di awal).
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Keterangan',
            'Jenis Pengeluaran',
            'Karyawan (Kasbon)',
            'Total',
        ];
    }

    /**
     * Memetakan data dari setiap model Pengeluaran ke dalam format baris Excel.
     *
     * @param Pengeluaran $pengeluaran The model instance from the query.
     * @return array
     */
    public function map($pengeluaran): array
    {
        return [
            ++$this->rowNumber,
            $pengeluaran->created_at->format('d M Y'), // Format tanggal yang mudah dibaca
            $pengeluaran->keterangan,
            $pengeluaran->jenis_pengeluaran,
            $pengeluaran->karyawan?->nama_karyawan ?? '-', // Menggunakan null-safe operator PHP 8
            $pengeluaran->total, // Mengirim data angka mentah agar bisa diformat sebagai currency
        ];
    }

    /**
     * Menerapkan format spesifik pada kolom tertentu.
     * Ini membuat kolom 'Total' menjadi tipe data Number (Currency) di Excel,
     * sehingga bisa dijumlahkan (SUM) dengan mudah oleh pengguna.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Format kolom Tanggal
            'F' => '"Rp"#,##0', // Format Rupiah kustom untuk kolom F (Total)
        ];
    }

    /**
     * Menerapkan styling pada worksheet, seperti membuat header menjadi bold.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Membuat baris pertama (header) menjadi tebal (bold).
            1 => ['font' => ['bold' => true]],
        ];
    }
}
