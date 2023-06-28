<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->datetime('mulai_hadir');
            $table->datetime('selesai_hadir')->nullable();
            $table->enum('jenis', ['h', 'l']);
            $table->integer('total_jam_hadir')->nullable()->comment('Dihitung berdasarkan detik');
            $table->unsignedInteger('karyawan_id');
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi');
    }
}
