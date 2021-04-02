<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriJenisDokumen;

class Dokumen extends Model
{
    use HasFactory;
    protected $table = 'dokumen';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'nomor',
        'tahun',
        'tags',
        'perihal',
        'isi',
        'file',
    ];    

    public function kategori()
    {
        return $this->belongsToMany(KategoriJenisDokumen::class,
            'kategori_dokumen',
            'dokumen_id',
            'kategori_jenis_dokumen_id');
    }
}
