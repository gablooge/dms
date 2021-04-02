<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis', 200);
            $table->string('keterangan', 255)->nullable();
            $table->bigInteger('kategori_jenis_dokumen_id')->unsigned();
            $table->index('kategori_jenis_dokumen_id');
            $table->foreign('kategori_jenis_dokumen_id')->references('id')->on('kategori_jenis_dokumen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_dokumen');
    }
}
