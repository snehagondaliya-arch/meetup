<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id','user_type', 'message','parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
