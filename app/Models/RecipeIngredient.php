<?php

namespace App\Models;
use Log;
use Illuminate\Database\Eloquent\Model;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class RecipeIngredient extends Model
{
    protected $fillable = ['name'];
    protected $hidden =['created_at', 'updated_at'];
    public $timestamps = false;

    public function usage() {
        return $this->hasMany('App\Models\RecipeIngredientSectionIngredient','ingredient_id');
    }
    public function getPriceForQuantity($quantity, $quantity_unit) {
        Log::info($quantity."-".$quantity_unit."-".$this->id);

        $quantity_unit = trim($quantity_unit);
        if($this->price_unit == $quantity_unit) {
            if($this->price == 0)
                return 44.4;
            return $this->price * $quantity;
        }

        if($this->price_unit == null)//price not set for ingredient
            return 33.3;


        if($quantity_unit=="pound" || $quantity_unit=="gram")
            $v = new Mass($quantity,$quantity_unit);
        else
            $v = new Volume($quantity,$quantity_unit);
        //return 'ok';
        return $v->toUnit($this->price_unit)*$this->price;


        Log::info($this->name." - ".$this->price_unit." vs ".$quantity_unit);
    }
}


