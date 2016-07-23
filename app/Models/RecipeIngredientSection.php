<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredientSection extends Model
{
    public function ingredients()
    {
        return $this->hasMany('App\Models\RecipeIngredientSectionIngredient','ingredient_section_id');
    }
}
