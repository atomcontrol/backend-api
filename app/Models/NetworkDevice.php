<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkDevice extends Model
{
    protected $fillable = [
        'mac','nickname',
    ];
    public function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
