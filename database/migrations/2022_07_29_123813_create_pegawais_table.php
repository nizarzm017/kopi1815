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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jenis_kelamin');
            $table->string('tempat');
            $table->date('tanggal_lahir');
            $table->integer('agama');
            $table->text('alamat');
            $table->string('no_hp');
            $table->integer('status_perkawinan');
            $table->integer('jabatan');
            $table->date('mulai_bekerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
};
