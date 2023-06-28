<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->text('pendidikan');
            $table->enum('jk', ['laki-laki', 'perempuan']);
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->enum('jam_kerja', ['full time', 'part time']);
            $table->text('tugas');
            $table->string('npwp', 30)->nullable();
            $table->string('bank', 30);
            $table->string('no_rek', 30);
            $table->date('awal_kontrak');
            $table->date('akhir_kontrak')->nullable();
            $table->string('foto')->nullable();
            $table->string('email');
            $table->string('telegram_id')->nullable();
            $table->enum('tingkat', ['senior', 'junior']);
            $table->boolean('status')->default(1);
            $table->unsignedInteger('jabatan_id');
            $table->unsignedInteger('level_id');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('jabatan_id')->references('id')->on('jabatan');
            $table->foreign('level_id')->references('id')->on('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
}
