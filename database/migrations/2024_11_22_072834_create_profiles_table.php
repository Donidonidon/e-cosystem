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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('users_id')->references('id')->on('users');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('nik')->unique();
            $table->string('no_hp');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('agama');

            $table->string('alamat');
            $table->integer('provinces_id');
            $table->integer('regencies_id');
            $table->integer('districts_id');

            $table->string('ijasah_terakhir');
            $table->integer('jabatan_sekarang')->references('id')->on('jabatans');
            $table->date('tanggal_masuk');
            $table->integer('kantor');
            $table->string('foto_ktp');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
