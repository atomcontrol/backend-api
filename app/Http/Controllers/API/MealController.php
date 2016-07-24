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
        $r = Meal::with('meal_recipe.recipe')->get();
        return $r;
    }
    public function shoppingList() {
        $meals = Meal::with(
            'meal_recipe.recipe',
            'meal_recipe.recipe.instruction_sections',
            'meal_recipe.recipe.ingredient_sections.ingredients.ingredient',
            'meal_recipe.recipe.ingredient_sections.ingredients.substitute'
        )->get()->toArray();

        $shoppingList = [];
        foreach($meals as $meal) {
            foreach($meal['meal_recipe'] as $mr) {
                //return $mr['recipe']['name'];
                $ing = Recipe::flattenIngredients($mr['recipe']);
                $ing = RecipeIngredient::scaleList($ing,$mr['portion']);
                foreach($ing as $eachIngredient) {
                    $shoppingList = RecipeIngredient::addToShoppingList($eachIngredient,$shoppingList);
                }
                //return($ing);
            }
        }
        return Meal::condense($shoppingList);
    }
//    public function show($slug) {
//        $r =  Recipe::with(Recipe::ALL_EAGER_CONSTRAINTS)->where('slug',$slug)->first();
//        return $r;//todo check for null
//    }
//    public function update($slug, Request $request) {
//        $r = Recipe::where('slug',$slug)->first();
//        $data = json_decode($request->get('json'),true);
//
//        foreach ($data as $k => $v) {
//            if(in_array($k,$r->getFillable())) {
//                $r->$k=$v;
//            }
//        }
//        $r->populateInstructionSections($data['instruction_sections']);
//        $r->populateIngredientSections($data['ingredient_sections']);
//        $r->save();
//
//        return $r->fresh(Recipe::ALL_EAGER_CONSTRAINTS);
//
//    }
}