<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealRecipe extends Model
{
    public function recipe() {
        return $this->hasOne('App\Models\Recipe','id','recipe_id');
    }

}
