<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable; // Opsional, untuk menggunakan konstruktor

class KaryawanExport implements FromQuery, WithHeadings
{
    use Exportable; // Menggunakan trait Exportable

    protected $startDate;
    protected $endDate;
    protected $searchQuery;

    public function __construct($startDate = null, $endDate = null, $searchQuery = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->searchQuery = $searchQuery;
    }

    public function query()
    {
        $query = Karyawan::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('nama', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('nik', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIK',
            'Nama Karyawan',
            'Email',
            'Nomor HP',
            'Alamat',
        ];
    }
}
