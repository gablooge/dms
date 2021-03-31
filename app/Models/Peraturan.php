<?php

namespace App\Models;

use App\Models\JenisPeraturan;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table = 'MASTER_PERATURAN';
    protected $primaryKey = 'ID';
    public $incrementing = true;
    public $timestamps = false;
    const UPDATED_AT = 'TGL_UPDATE';
    const CREATED_AT = 'TGL_UPDATE';

    protected $guarded = [];

    public function jenis_peraturan()
    {
        return $this->belongsTo(JenisPeraturan::class, 'IDJENIS');
    }
}
