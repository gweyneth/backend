<?php

namespace App\Exports;

use App\Models\GajiKaryawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class GajiKaryawanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle, WithStyles
{
    protected $selectedMonth;
    protected $searchQuery;

    public function __construct($selectedMonth = null, $searchQuery = null)
    {
        $this->selectedMonth = $selectedMonth;
        $this->searchQuery = $searchQuery;
    }

    /**
     * Metode `collection()` akan mengambil data yang akan diekspor.
     * Filter berdasarkan bulan dan pencarian nama karyawan akan diterapkan di sini.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = GajiKaryawan::with(['karyawan', 'kasbon'])->latest();

        // Terapkan filter bulan jika ada
        if ($this->selectedMonth) {
            $carbonMonth = Carbon::parse($this->selectedMonth);
            $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
            $endOfMonth = $carbonMonth->endOfMonth()->toDateString();
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }

        // Terapkan filter pencarian nama karyawan jika ada
        if ($this->searchQuery) {
            $query->whereHas('karyawan', function ($q) {
                $q->where('nama_karyawan', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query->get();
    }

    /**
     * Metode `headings()` akan mendefinisikan judul kolom (header) di baris pertama file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No', // Nomor urut di Excel
            'Nama Karyawan',
            'Jumlah Gaji',
            'Bonus (%)',
            'Jumlah Bonus',
            'Kasbon Terkait',
            'Sisa Gaji',
            'Status Pembayaran',
            'Tanggal Input',
        ];
    }

    /**
     * Metode `map()` akan memetakan setiap objek GajiKaryawan menjadi baris data di Excel.
     * Ini memungkinkan kita untuk memformat data atau menggabungkan data dari relasi.
     *
     * @param mixed $gajiKaryawan Objek model GajiKaryawan
     * @return array Array yang merepresentasikan satu baris di Excel
     */
    public function map($gajiKaryawan): array
    {
        static $rowNumber = 0; // Inisialisasi nomor baris statis
        $rowNumber++; // Tingkatkan nomor baris setiap kali map dipanggil

        return [
            $rowNumber, // Nomor urut
            $gajiKaryawan->karyawan->nama_karyawan ?? 'N/A',
            $gajiKaryawan->jumlah_gaji,
            $gajiKaryawan->bonus_persen,
            $gajiKaryawan->jumlah_bonus,
            $gajiKaryawan->kasbon->keterangan . ' (Rp' . number_format($gajiKaryawan->kasbon->total, 0, ',', '.') . ')' ?? '-',
            $gajiKaryawan->sisa_gaji,
            ucwords($gajiKaryawan->status_pembayaran),
            $gajiKaryawan->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Metode `title()` akan mengatur nama sheet di file Excel.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Gaji Karyawan ' . date('M Y', strtotime($this->selectedMonth));
    }

    /**
     * Metode `styles()` akan menerapkan styling pada sheet Excel.
     * Di sini kita membuat baris pertama (header) menjadi tebal.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
