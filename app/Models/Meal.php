<?php

namespace App\Models;
use Log;
use Illuminate\Database\Eloquent\Model;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class Meal extends Model
{
    public static function condense($shoppingList)
    {
        $neutralizeUnits = function($a) {
            //Log::info($a);
            //$a['quantity']=69;
            $unit = $a['quantity_unit'];
            if($unit!="") {
                if($unit == 'pound') {
                    $a['quantity'] = (new Mass($a['quantity'], $unit))->toUnit('pound');
                    $a['quantity_endrange'] = (new Mass($a['quantity_endrange'], $unit))->toUnit('pound');
                }
                else {
                    $a['quantity'] = (new Volume($a['quantity'], $unit))->toUnit('cup');
                    $a['quantity_endrange'] = (new Volume($a['quantity_endrange'], $unit))->toUnit('cup');
                }
                $a['quantity_unit'] = 'cup';
            }
            return $a;
        };
        $combineUnits = function($carry, $item) {
            return [
                'quantity'=> $carry['quantity']+ $item['quantity'],
                'grams'=> $carry['grams']+ $item['grams'],
                'quantity_unit'=> $item['quantity_unit']
            ];
        };

        foreach($shoppingList as &$listItem) {
            $listItem['items'] = array_map($neutralizeUnits,$listItem['items']);
            $listItem['items'] = array_reduce($listItem['items'],$combineUnits);
        }
        return $shoppingList;

    }

    public function meal_recipe()
    {
        return $this->hasMany('App\Models\MealRecipe');
    }
}
