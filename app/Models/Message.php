<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'messageable_id',
        'messageable_type',
        'message',
        'parent_id'
    ];

    public function messageable()
    {
        return $this->morphTo();
    }
    
    // public function senderName()
    // {
    //     if($this->user_type == 'user'){
    //         return $this->belongsTo(User::class, 'user_id');
    //     }else{
    //         return $this->belongsTo(Organization::class, 'user_id')->select('id','organization_name as first_name','profile');
    //     }
    // }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
