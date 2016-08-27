<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Auth;
use Illuminate\Http\Request;
use Input;
use Log;
use AWS;
class MealController extends Controller {


    public function index() {
        $m = Meal::with('meal_recipe.recipe')->get();
        foreach ($m as &$each) {
            foreach($each['meal_recipe'] as &$r) {
                $r['p']="asd";
                $r['cost']=$r['recipe']->getCost($r['portion']);

            }
        }
        return $m;
    }
    public function shoppingList() {
        $meals = Meal::with(
            'meal_recipe.recipe.instruction_sections',
            'meal_recipe.recipe.ingredient_sections.ingredients.ingredient',
            'meal_recipe.recipe.ingredient_sections.ingredients.substitute'
        )->get()->toArray();

        $shoppingList = [];
        foreach($meals as $meal) {
            foreach($meal['meal_recipe'] as $mr) {
                //return $mr['recipe']['name'];
                $ingredients = self::flattenIngredients($mr['recipe']);
                $ingredients = self::scaleList($ingredients,$mr['portion']);
                foreach($ingredients as $eachIngredient) {
                    $shoppingList = self::addToShoppingList($eachIngredient,$shoppingList);
                }
            }
        }
        return Meal::condense($shoppingList);
    }

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
        $ingredientId = $ingredient['ingredient']['id'];
        $list[$ingredientId]['deps'][]=$ingredient;//add the ingredient to the list dependencies
        $list[$ingredientId]['name']=$ingredient['ingredient']['name'];

        //clear out unneeded things
        unset($ingredient['recipe_id']);
        unset($ingredient['recipe_name']);
        unset($ingredient['substitute']);
        unset($ingredient['ingredient']);

        $list[$ingredientId]['items'][]=$ingredient;
        return $list;
    }
    public static function flattenIngredients($deepRecipe) {
        $result = [];
        foreach ($deepRecipe['ingredient_sections'] as $sect) {
            foreach($sect['ingredients'] as $ingr) {
                //pointers back to the recipe
                $ingr['recipe_name']=$deepRecipe['name'];
                $ingr['recipe_id']=$deepRecipe['id'];
                //get rid of extraneous things
                unset($ingr['ingredient_section_id']);
                unset($ingr['ingredient_id']);
                unset($ingr['ingredient_substitute_id']);
                unset($ingr['extras']);
                unset($ingr['id']);
                $result[]=$ingr;
            }
        }
        return $result;
    }
}