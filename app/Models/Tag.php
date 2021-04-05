<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tag';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    
    protected $fillable = [
        'nama_tag',
        'keterangan',
    ]; 

    public function getTextAttribute()
    {
        return $this->nama_tag;
    }

    public function dokumen()
    {
        return $this->belongsToMany(Dokumen::class,
            'tag_dokumen',
            'tag_id',
            'dokumen_id');
    }
}
