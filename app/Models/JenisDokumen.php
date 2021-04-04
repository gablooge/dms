<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    use HasFactory;
    protected $table = 'jenis_dokumen';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    
    protected $fillable = [
        'nama_jenis',
        'keterangan',
        'kategori_jenis_dokumen_id',
    ]; 
}
