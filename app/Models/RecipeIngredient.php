<?php

namespace App\Models;
use Log;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $fillable = ['name'];
    protected $hidden =['created_at', 'updated_at'];
    public $timestamps = false;
    public static function scaleList($list,$scale) {
        foreach($list as &$value)
        {
            $value['quantity'] = $value['quantity']*$scale;
            $value['quantity_endrange'] = $value['quantity_endrange']*$scale;
            $value['grams'] = $value['grams']*$scale;
        }
        return $list;
    }

    public static function addToShoppingList($ingredient,$list) {




        $id = $ingredient['ingredient']['id'];
        $list[$id]['deps'][]=$ingredient;
        $list[$id]['name']=$ingredient['ingredient']['name'];

        unset($ingredient['recipe_id']);
        unset($ingredient['recipe_name']);
        unset($ingredient['substitute']);
        unset($ingredient['ingredient']);


        $list[$id]['items'][]=$ingredient;//array_map($neutralizeUnits,$ingredient);


        return $list;
    }
}
