<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    protected $table = 'komponen';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function jabatan()
    {
        return $this->belongsTo('App\Models\Jabatan', 'jabatan_id', 'id');
    }
}
