<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
