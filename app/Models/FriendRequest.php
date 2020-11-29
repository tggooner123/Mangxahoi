<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    public function user() {
        return $this->belongsTo('App\User', 'user_send_request');
    }
}
