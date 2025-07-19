<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendapatanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected array $filters;
    private int $rowNumber = 0;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Query untuk mengambil data pendapatan.
     * Logikanya sama persis dengan di controller.
     */
    public function query()
    {
        $query = Transaksi::with(['pelanggan', 'rekening'])
            ->where(function ($q) {
                $q->where('sisa', '=', 0) // Lunas
                  ->orWhere('uang_muka', '>', 0); // Atau ada DP
            })
            ->latest('updated_at');

        $request = request()->merge($this->filters);

        $query->when($request->filled('search_query'), function ($q) use ($request) {
            $search = $request->input('search_query');
            $q->where(function ($subq) use ($search) {
                $subq->where('no_transaksi', 'like', "%{$search}%")
                     ->orWhereHas('pelanggan', fn($p) => $p->where('nama', 'like', "%{$search}%"));
            });
        });

        $query->when($request->filled('start_date'), fn($q) => $q->whereDate('updated_at', '>=', $request->input('start_date')));
        $query->when($request->filled('end_date'), fn($q) => $q->whereDate('updated_at', '<=', $request->input('end_date')));

        $query->when($request->filled('metode_pembayaran') && $request->input('metode_pembayaran') !== 'all', function ($q) use ($request) {
            $q->where('metode_pembayaran', $request->input('metode_pembayaran'));
        });

        $query->when($request->input('metode_pembayaran') === 'transfer_bank' && $request->filled('rekening_id'), function ($q) use ($request) {
            $q->where('rekening_id', $request->input('rekening_id'));
        });

        return $query;
    }

    /**
     * Mendefinisikan header untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'No. Transaksi',
            'Nama Pelanggan',
            'Tanggal Bayar',
            'Metode Pembayaran',
            'Bank (Jika Transfer)',
            'Jumlah Dibayar',
            'Total Transaksi',
        ];
    }

    /**
     * Memetakan data untuk setiap baris di Excel.
     */
    public function map($transaksi): array
    {
        return [
            ++$this->rowNumber,
            $transaksi->no_transaksi,
            $transaksi->pelanggan->nama ?? 'Umum',
            $transaksi->updated_at->format('d-m-Y'),
            ucwords(str_replace('_', ' ', $transaksi->metode_pembayaran ?? '-')),
            $transaksi->rekening->bank ?? '-',
            $transaksi->uang_muka, // Jumlah yang sudah dibayar
            $transaksi->total,
        ];
    }

    /**
     * Menerapkan format angka pada kolom.
     */
    public function columnFormats(): array
    {
        return [
            'G' => '"Rp"#,##0', // Format Rupiah untuk Jumlah Dibayar
            'H' => '"Rp"#,##0', // Format Rupiah untuk Total Transaksi
        ];
    }

    /**
     * Menerapkan style pada header.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
