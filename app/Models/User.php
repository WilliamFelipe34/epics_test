<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\UserLog;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'document_number',
        'phone_number',
        'country'
    ];



}
