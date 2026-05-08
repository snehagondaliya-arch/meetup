<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Organization extends Authenticatable
{
    protected $fillable = [
        'organization_name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
