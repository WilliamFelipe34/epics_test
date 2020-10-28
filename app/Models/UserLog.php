<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserLog extends Authenticatable
{
    protected $table = 'user_logs';

    protected $fillable = [
        'id',
        'user_id',
        'data_old',
        'data_new'
    ];


}
