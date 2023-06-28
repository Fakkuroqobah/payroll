<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKontrakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kontrak', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->integer('nominal')->nullable();
            $table->text('keterangan');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['l', 'bl'])->default('bl');
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
        Schema::dropIfExists('kontrak');
    }
}
