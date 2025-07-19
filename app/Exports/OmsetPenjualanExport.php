<?php

namespace App\Exports;

use App\Models\TransaksiDetail; // Ganti dengan model detail transaksi Anda
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OmsetPenjualanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $filters;
    private int $rowNumber = 0;

    /**
     * @param array|\Illuminate\Support\Collection $filters Filter dari request (produk_id, bulan).
     */
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Query untuk mengambil data omset penjualan produk.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Asumsi: Model TransaksiDetail memiliki relasi 'produk'
        // CATATAN: Pastikan nama kolom untuk jumlah/kuantitas sudah benar.
        // Error sebelumnya menunjukkan kolom 'jumlah' tidak ditemukan.
        // Saya mengubahnya menjadi 'qty'. Sesuaikan jika nama kolom Anda berbeda (misalnya: quantity, jumlah_item, dll).
        $query = TransaksiDetail::with('produk')
            ->selectRaw('produk_id, SUM(qty) as jumlah_terjual, SUM(total) as total_omset')
            ->groupBy('produk_id')
            ->orderByRaw('SUM(total) DESC');

        // Filter berdasarkan produk
        $query->when($this->filters['produk_id'] ?? null, function ($q, $produkId) {
            if ($produkId !== 'all') {
                return $q->where('produk_id', $produkId);
            }
        });

        // Filter berdasarkan bulan dan tahun
        $query->when($this->filters['bulan'] ?? null, function ($q, $bulan) {
            // $bulan formatnya 'YYYY-MM'
            [$year, $month] = explode('-', $bulan);
            return $q->whereYear('created_at', $year)->whereMonth('created_at', $month);
        });

        return $query;
    }

    /**
     * Mendefinisikan header untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Produk',
            'Jumlah Terjual',
            'Total Omset',
        ];
    }

    /**
     * Memetakan data untuk setiap baris di Excel.
     *
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            ++$this->rowNumber,
            $row->produk?->nama ?? 'Produk Tidak Ditemukan',
            $row->jumlah_terjual,
            $row->total_omset,
        ];
    }

    /**
     * Menerapkan format angka pada kolom.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Format untuk Jumlah
            'D' => '"Rp"#,##0', // Format Rupiah untuk Total Omset
        ];
    }

    /**
     * Menerapkan style pada header.
     *
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Membuat header (baris 1) menjadi bold.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
