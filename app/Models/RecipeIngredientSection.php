<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredientSection extends Model
{
    public $timestamps = false;
    protected $hidden =['created_at', 'updated_at'];
    public function ingredients()
    {
        return $this->hasMany('App\Models\RecipeIngredientSectionIngredient','ingredient_section_id');
    }
}
