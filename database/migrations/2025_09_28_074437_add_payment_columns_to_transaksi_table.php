<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Cek sebelum menambahkan setiap kolom agar migrasi aman dijalankan ulang
            if (!Schema::hasColumn('transaksi', 'status_bayar')) {
                $table->string('status_bayar', 50)->default('BELUM LUNAS')->after('status_pengerjaan');
            }
            
            if (!Schema::hasColumn('transaksi', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 50)->nullable()->after('status_bayar');
            }
            
            if (!Schema::hasColumn('transaksi', 'rekening_id')) {
                $table->foreignId('rekening_id')->nullable()->constrained('rekening')->onDelete('set null')->after('metode_pembayaran');
            }
            
            if (!Schema::hasColumn('transaksi', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('rekening_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Kolom yang akan dihapus
            $columns = ['status_bayar', 'metode_pembayaran', 'rekening_id', 'bukti_pembayaran'];

            // Cek foreign key sebelum menghapus
            if (Schema::hasColumn('transaksi', 'rekening_id')) {
                 // Hapus constraint terlebih dahulu agar tidak error
                $table->dropForeign(['rekening_id']);
            }

            // Hapus setiap kolom jika ada
            foreach ($columns as $column) {
                if (Schema::hasColumn('transaksi', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

