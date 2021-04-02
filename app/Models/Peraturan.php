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
        return $this->belongsTo(JenisPeraturan::class, 'idjenis', 'idjenis');
    }
    // Sepertinya masih salah table nya
    // public function getTagAttribute($value)
    // {
    //     if(is_null($value) || empty($value)){
    //         return "-";
    //     }
    //     $integerIDs = array_map('intval', explode(',', $value));
    //     // die($integerIDs);
    //     $tags = Tag::select('masalah')->whereIn('idmslh', $integerIDs)->get();
    //     return $tags->implode('masalah', ', ');
    // }
    public function getLampAttribute($value)
    {
        if(is_null($value) || empty($value)){
            return "-";
        }
        return $value;
    }
    public function getFileAttribute()
    {
        if($this->lamp == "-"){
            return "-";
        }
        return "http://10.15.90.180/peraturan/files/".$this->lamp;
    }
}
