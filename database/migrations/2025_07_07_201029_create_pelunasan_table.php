<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['tunai', 'transfer_bank'])->nullable()->after('status_pengerjaan');
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            $table->foreignId('rekening_id')->nullable()->constrained('rekening')->onDelete('set null')->after('bukti_pembayaran');
            $table->text('keterangan_pembayaran')->nullable()->after('rekening_id');
        });
    }
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['rekening_id']); 
            $table->dropColumn([
                'metode_pembayaran',
                'bukti_pembayaran',
                'rekening_id',
                'keterangan_pembayaran',
            ]);
        });
    }
};
