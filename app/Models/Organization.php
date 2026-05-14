<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Organization extends Authenticatable
{
    protected $table = 'organizations';
    protected $fillable = [
        'organization_name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected $casts = [
        'password' => 'hashed',
    ];

    public function events(){   
        return $this->hasMany(Event::class);
    }

    public function messages(){
        return $this->morphMany(Message::class,'messageable');
    }
}
