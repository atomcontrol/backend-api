<?php

namespace App\Models;
use Log;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $fillable = ['name'];
    protected $hidden =['created_at', 'updated_at'];
    public $timestamps = false;
}
