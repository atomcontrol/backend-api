<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredientSectionIngredient extends Model
{
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'recipe_section_ingredients';
    public function ingredient()
    {
        return $this->hasOne('App\Models\RecipeIngredient','id','ingredient_id');
    }
    public function substitute()
    {
        return $this->hasOne('App\Models\RecipeIngredient','id','ingredient_substitute_id');
    }
}
