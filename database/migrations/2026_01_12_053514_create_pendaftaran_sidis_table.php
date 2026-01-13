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
        Schema::create('pendaftaran_sidis', function (Blueprint $table) {
            $table->id();

            // Data Pribadi Calon Sidi
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->text('alamat')->nullable();
            $table->string('wijk')->nullable(); // teks, bisa calon non-jemaat

            // Kelengkapan Administrasi (upload file path)
            $table->string('foto_4x6')->nullable();
            $table->string('foto_3x4')->nullable();
            $table->string('surat_baptis')->nullable();
            $table->string('kartu_keluarga')->nullable();
            $table->string('surat_pengantar_sintua')->nullable();

            // Status pengajuan & catatan admin
            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();

            // Jenis pendaftar (jemaat internal atau jemaat eksternal)
            $table->enum('jenis_pendaftar', ['internal', 'external'])->default('external');

            // jemaat_id nullable, one-to-many (many pendaftaran_sidis per jemaat)
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaats')->nullOnDelete();

            $table->foreignId('katekisasi_id')
                ->constrained('katekisasis')
                ->onDelete('cascade'); // jika katekisasi dihapus, pendaftaran terkait ikut terhapus




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_sidis');
    }
};
