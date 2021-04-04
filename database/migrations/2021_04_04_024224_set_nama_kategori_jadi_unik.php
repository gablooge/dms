<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNamaKategoriJadiUnik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategori_jenis_dokumen', function($table) {
            $table->unique('nama_kategori', 'kategori_nama_kategori_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kategori_jenis_dokumen', function($table) {
            $table->dropUnique('kategori_nama_kategori_unique');
        });
    }
}
