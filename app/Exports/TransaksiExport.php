<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle; // Ditambahkan untuk judul sheet
use Maatwebsite\Excel\Concerns\WithStyles; // Ditambahkan untuk styling
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Digunakan oleh WithStyles

class TransaksiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle, WithStyles
{
    /**
    * Metode `collection()` akan mengambil data yang akan diekspor.
    * Kita mengambil semua transaksi dengan eager loading relasi 'pelanggan' dan 'transaksiDetails'.
    * Anda bisa menambahkan filter di sini jika ingin mengekspor data berdasarkan kriteria tertentu (misal: tanggal).
    *
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaksi::with(['pelanggan', 'transaksiDetails'])->get();
    }

    /**
     * Metode `headings()` akan mendefinisikan judul kolom (header) di baris pertama file Excel.
     * Kolom 'ID Pelunasan' dihapus untuk hanya menampilkan data penting.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal Order',
            'Tanggal Selesai',
            'Nama Pelanggan',
            'Total (Setelah Diskon)',
            'Uang Muka',
            'Diskon',
            'Sisa Pembayaran',
            'Status Pengerjaan',
            'Detail Produk', // Kolom untuk menggabungkan detail produk
        ];
    }

    /**
     * Metode `map()` akan memetakan setiap objek Transaksi menjadi baris data di Excel.
     * Ini memungkinkan kita untuk memformat data atau menggabungkan data dari relasi.
     *
     * @param mixed $transaksi Objek model Transaksi
     * @return array Array yang merepresentasikan satu baris di Excel
     */
    public function map($transaksi): array
    {
        // Format detail produk menjadi string yang mudah dibaca, dipisahkan dengan newline
        // Setiap detail produk akan menjadi satu baris dalam sel Excel
        $detailProduk = $transaksi->transaksiDetails->map(function ($detail) {
            return sprintf(
                "%s (Qty: %d, Harga: Rp%s, Total: Rp%s)",
                $detail->nama_produk,
                $detail->qty,
                number_format($detail->harga, 0, ',', '.'), // Format harga
                number_format($detail->total, 0, ',', '.')  // Format total detail
            );
        })->implode("\n"); // Gabungkan semua detail produk dengan newline

        return [
            $transaksi->no_transaksi,
            $transaksi->tanggal_order->format('d/m/Y'), // Format tanggal order
            $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('d/m/Y') : '-', // Format tanggal selesai (jika ada)
            $transaksi->pelanggan->nama ?? 'Umum', // Nama pelanggan, atau 'Umum' jika tidak ada
            $transaksi->total, // Total transaksi (sudah setelah diskon)
            $transaksi->uang_muka,
            $transaksi->diskon,
            $transaksi->sisa,
            ucwords(str_replace('_', ' ', $transaksi->status_pengerjaan)), // Status pengerjaan diformat
            $detailProduk, // String detail produk yang sudah digabungkan
        ];
    }

    /**
     * Metode `title()` akan mengatur nama sheet di file Excel.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Transaksi';
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
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
