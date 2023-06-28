<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Authenticatable
{
    use Notifiable;

    protected $table = 'karyawan';
    protected $guarded = ['id'];
    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function jabatan()
    {
        return $this->belongsTo('App\Models\Jabatan', 'jabatan_id', 'id');
    }

    public function gajiTerbaru()
    {
        return $this->hasOne('App\Models\Gaji', 'karyawan_id', 'id')->orderBy('id', 'DESC');
    }
}
