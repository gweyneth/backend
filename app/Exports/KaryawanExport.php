<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;

class KaryawanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $searchQuery;

    public function __construct($startDate = null, $endDate = null, $searchQuery = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->searchQuery = $searchQuery;
    }

    /**
     * Menjalankan query untuk mengambil data karyawan dengan filter.
     */
    public function query()
    {
        $query = Karyawan::query()->latest();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('nama_karyawan', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('nik', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query;
    }

    /**
     * Mendefinisikan judul untuk setiap kolom.
     */
    public function headings(): array
    {
        return [
            'ID Karyawan',
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Status',
            'Email',
            'No. Handphone',
            'Gaji Pokok',
            'Alamat',
            'Tanggal Bergabung',
        ];
    }

    /**
     * Memetakan data yang ingin ditampilkan untuk setiap baris.
     */
    public function map($karyawan): array
    {
        return [
            $karyawan->id_karyawan,
            $karyawan->nik,
            $karyawan->nama_karyawan,
            $karyawan->jabatan,
            $karyawan->status,
            $karyawan->email,
            $karyawan->no_handphone,
            $karyawan->gaji_pokok,
            $karyawan->alamat,
            $karyawan->created_at->format('d-m-Y H:i:s'),
        ];
    }

    /**
     * Menerapkan style ke worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk baris header (baris pertama)
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF007BFF'],
            ],
        ]);

        // Mengatur format kolom Gaji Pokok menjadi format mata uang Rupiah
        $lastRow = $sheet->getHighestRow();
        // PERBAIKAN: Menggunakan format string yang benar untuk Rupiah dengan spasi dan pemisah ribuan (titik)
        $sheet->getStyle('H2:H' . $lastRow)->getNumberFormat()
        ->setFormatCode('Rp #.##0');

        // Menambahkan border ke semua sel yang terisi data
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }
}
