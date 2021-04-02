<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dokumen;

class KategoriJenisDokumen extends Model
{
    use HasFactory;
    protected $fillable = [
        'kategori_jenis_dokumen',
        'keterangan',
    ]; 

    public function dokumen()
    {
        return $this->belongsToMany(Dokumen::class,
            'kategori_dokumen',
            'kategori_jenis_dokumen_id',
            'dokumen_id');
    }
}
