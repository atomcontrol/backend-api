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
    public function recipe() {
        return $this->hasOne('App\Models\Recipe','id','recipe_id');
    }
    public function calculateSectionCost($scale) {
        return 1;
        $totalPrice = 0;
        $result = [];
        foreach( $this->ingredients as $eachIngredient ) {
            $price = $eachIngredient->ingredient->getPriceForQuantity($eachIngredient['quantity'], $eachIngredient['quantity_unit']);
            $totalPrice+=$price;
            $result['items'][]=[
                'price'=>$price,
                'quantity_unit'=>$eachIngredient['quantity_unit'],
                'name'=>$eachIngredient['ingredient']['name'],
                'id'=>$eachIngredient['ingredient']['id']
            ];
            if(false) {
                echo $eachIngredient['ingredient']['name'];
                echo($price);
                echo "<hr/>";
            }
        }
        $result['total'] = $totalPrice*$scale;
        return $result;
    }
}
