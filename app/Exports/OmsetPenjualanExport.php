<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OmsetPenjualanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    protected $omsetProduk;
    protected $subtotalOmset;
    protected $selectedMonth;

    public function __construct(Collection $omsetProduk, $subtotalOmset, $selectedMonth)
    {
        $this->omsetProduk = $omsetProduk;
        $this->subtotalOmset = $subtotalOmset;
        $this->selectedMonth = $selectedMonth;
    }
    public function collection()
    {
        $data = $this->omsetProduk->map(function ($item) {
            return [
                $item['nama_produk'],
                $item['jumlah'],
                $item['total'],
            ];
        });

        // Tambahkan baris subtotal
        $data->push([
            'Subtotal Omset Keseluruhan', // Kolom pertama
            '',                           // Kolom kedua kosong
            $this->subtotalOmset          // Total omset di kolom ketiga
        ]);

        return $data;
    }

    /**
     * Metode `headings()` akan mendefinisikan judul kolom (header) di baris pertama file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Produk',
            'Jumlah Terjual',
            'Total Omset',
        ];
    }

    /**
     * Metode `title()` akan mengatur nama sheet di file Excel.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Omset Penjualan ' . date('M Y', strtotime($this->selectedMonth));
    }

    /**
     * Metode `styles()` akan menerapkan styling pada sheet Excel.
     * Membuat baris pertama (header) menjadi tebal, dan baris terakhir (subtotal) juga tebal.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Mendapatkan jumlah baris data ditambah header dan subtotal
        $lastRow = $this->omsetProduk->count() + 2; // +1 untuk header, +1 untuk subtotal

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
            // Style the last row (subtotal) as bold text.
            $lastRow => ['font' => ['bold' => true]],
        ];
    }
}
