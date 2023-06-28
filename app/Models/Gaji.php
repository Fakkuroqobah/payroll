<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $table = 'kontrak';
    protected $guarded = ['id'];
    public $timestamps = true;
}
