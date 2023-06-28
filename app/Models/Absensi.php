<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function todo()
    {
        return $this->hasMany('App\Models\Todo', 'absensi_id', 'id');
    }
}
