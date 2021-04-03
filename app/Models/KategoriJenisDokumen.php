<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dokumen;

class KategoriJenisDokumen extends Model
{
    use HasFactory;
    protected $table = 'kategori_jenis_dokumen';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    
    protected $fillable = [
        'nama_kategori',
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
