<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkDevice extends Model
{
    public function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
