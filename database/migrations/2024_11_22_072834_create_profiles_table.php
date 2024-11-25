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
            $table->unsignedBigInteger('users_id')->references('id')->on('users');

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

            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('subdistrict_id')->nullable();

            $table->string('ijasah_terakhir');
            $table->integer('divisi')->references('id')->on('divisis');
            $table->integer('jabatan_sekarang')->references('id')->on('jabatans');
            $table->date('tanggal_masuk');
            $table->integer('kantor');
            $table->string('foto_ktp');

            $table->timestamps();
            $table->softDeletes();
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
