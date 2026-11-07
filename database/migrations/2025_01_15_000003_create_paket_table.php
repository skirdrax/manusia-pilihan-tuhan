<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->string('no_resi', 30)->unique();
            $table->string('pengirim', 100);
            $table->string('penerima', 100);
            $table->text('alamat_tujuan');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['pending', 'dikirim', 'terkirim'])->default('pending');
            $table->foreignId('kurir_id')
                  ->nullable()
                  ->constrained('kurir')
                  ->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('kurir_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};

