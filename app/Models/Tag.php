<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'REF_MASALAH';
    protected $primaryKey = 'IDMSLH';
    public $incrementing = true;
    public $timestamps = false;

    protected $guarded = [];

    public function peraturan()
    {
        return $this->hasMany(Peraturan::class, 'tag');
    }
}
