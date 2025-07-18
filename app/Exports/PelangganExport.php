<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk lebar kolom otomatis
use Maatwebsite\Excel\Concerns\WithStyles;    // Untuk styling
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelangganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua data pelanggan, diurutkan berdasarkan yang terbaru
        return Pelanggan::latest()->get();
    }

    /**
     * Mendefinisikan judul untuk setiap kolom.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Pelanggan',
            'Nama',
            'Email',
            'No. Handphone',
            'Alamat',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Memetakan data yang ingin ditampilkan untuk setiap baris.
     * @param mixed $pelanggan
     * @return array
     */
    public function map($pelanggan): array
    {
        return [
            $pelanggan->kode_pelanggan,
            $pelanggan->nama,
            $pelanggan->email,
            $pelanggan->no_hp,
            $pelanggan->alamat,
            $pelanggan->created_at->format('d-m-Y H:i:s'),
        ];
    }

    /**
     * Menerapkan style ke worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk baris header (baris pertama)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Warna teks putih
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF007BFF', // Warna latar biru solid
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Menambahkan border ke semua sel yang terisi data
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }
}
