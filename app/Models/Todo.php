<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Todo extends Model
{
    protected $table = 'todo';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function absensi()
    {
        return $this->belongsTo('App\Models\Absensi', 'absensi_id', 'id')->where('karyawan_id', Auth::user()->id);
    }

    public function absensiAdmin()
    {
        return $this->belongsTo('App\Models\Absensi', 'absensi_id', 'id');
    }
}
