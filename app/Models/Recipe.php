<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Recipe extends Model
{
    const ALL_EAGER_CONSTRAINTS = [
        'instruction_sections',
        'ingredient_sections.ingredients.ingredient',
        'ingredient_sections.ingredients.substitute'
    ];
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'serves',
        'makes_quantity',
        'makes_noun',
        'time_total',
        'time_details',
        'source'];

    public function instruction_sections()
    {
        return $this->hasMany('App\Models\RecipeInstructionSection');
    }
    public function ingredient_sections()
    {
        return $this->hasMany('App\Models\RecipeIngredientSection');
    }
    public function populateInstructionSections($data) {
       RecipeInstructionSection::where('recipe_id',$this->id)->delete();
       foreach ($data as $eachInstruction) {
           $i = new RecipeInstructionSection();
           $i->title = $eachInstruction['title'];
           $i->body = $eachInstruction['body'];
           $i->recipe_id = $this->id;
           $i->save();
       }
       return;
    }
    public function populateIngredientSections($data)
    {
        //nuke the old ones
        $existingSections = RecipeIngredientSection::where('recipe_id',$this->id)->lists('id')->toArray();
        RecipeIngredientSectionIngredient::whereIn('ingredient_section_id',$existingSections)->delete();
        RecipeIngredientSection::where('recipe_id',$this->id)->delete();

        //rebuild
        Log::info($data);
        foreach ($data as $eachIngredientSection) {
            Log::info($eachIngredientSection['title']);
            $section = new RecipeIngredientSection();
            $section->title = $eachIngredientSection['title'];
            $section->recipe_id = $this->id;
            $section->save();
//            Log::info($section->id);
            foreach($eachIngredientSection['ingredients'] as $eachIngredient) {
                Log::info($eachIngredient['grams']);
                $mapping = new RecipeIngredientSectionIngredient();
                $mapping->ingredient_section_id = $section->id;
                $mapping->ingredient_id = RecipeIngredient::firstOrCreate(['name'=>$eachIngredient['ingredient']['name']])->id;//todo, error checking for ingredient array existing
                //$mapping->ingredient_substitute_id = 1; TODO, can be null
                $mapping->grams = $eachIngredient['grams'];
                $mapping->quantity = $eachIngredient['quantity'];
                $mapping->quantity_endrange = $eachIngredient['quantity_endrange'];
                $mapping->quantity_unit = $eachIngredient['quantity_unit'];
                $mapping->extras = $eachIngredient['extras'];
                $mapping->save();

            }
        }
    }
    public static function flattenIngredients($deepRecipe) {
        $result = [];
        foreach ($deepRecipe['ingredient_sections'] as $sect) {
            foreach($sect['ingredients'] as $ingr) {
                $ingr['recipe_name']=$deepRecipe['name'];
                $ingr['recipe_id']=$deepRecipe['id'];
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
