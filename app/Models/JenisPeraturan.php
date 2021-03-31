<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPeraturan extends Model
{
    use HasFactory;
    protected $table = 'REF_JNS_PERATURAN2';
    protected $primaryKey = 'IDJENIS';
    public $incrementing = true;
    public $timestamps = false;

    protected $guarded = [];
}
