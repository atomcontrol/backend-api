<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Auth;
use Illuminate\Http\Request;
use Input;
use Log;
use AWS;
class RecipeController extends Controller {


    public function index() {
        $r = Recipe::all();
        return $r;
    }
    public function show($slug) {
        $r =  Recipe::with(Recipe::ALL_EAGER_CONSTRAINTS)->where('slug',$slug)->first();
        return $r;//todo check for null
    }
    public function update($slug, Request $request) {
        $r = Recipe::where('slug',$slug)->first();
        $data = json_decode($request->get('json'),true);

        foreach ($data as $k => $v) {
            if(in_array($k,$r->getFillable())) {
                $r->$k=$v;
            }
        }
        $r->populateInstructionSections($data['instruction_sections']);
        $r->populateIngredientSections($data['ingredient_sections']);
        $r->save();

        return $r->fresh(Recipe::ALL_EAGER_CONSTRAINTS);
        
    }
}