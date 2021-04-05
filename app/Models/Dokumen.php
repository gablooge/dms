<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriJenisDokumen;
use App\Models\Tag;

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

    public function getFileAttribute($value)
    {
        if(is_null($value) || empty($value)){
            return "-";
        }
        return $value;
    }

    public function tags_list()
    {
        return $this->belongsToMany(Tag::class, 'tag_dokumen');
    }

    public function kategori_list()
    {
        return $this->belongsToMany(KategoriJenisDokumen::class,
            'kategori_dokumen',
            'dokumen_id',
            'kategori_jenis_dokumen_id');
    }
}
