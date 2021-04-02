<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_dokumen', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dokumen_id')->unsigned();
            $table->foreign('dokumen_id')
                ->references('id')
                ->on('dokumen')->onDelete('cascade');
            $table->bigInteger('kategori_jenis_dokumen_id')->unsigned();
            $table->foreign('kategori_jenis_dokumen_id')
                ->references('id')
                ->on('kategori_jenis_dokumen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_dokumen');
    }
}
