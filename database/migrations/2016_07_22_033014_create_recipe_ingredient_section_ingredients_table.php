<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeIngredientSectionIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('recipe_section_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ingredient_section_id')->unsigned();
            $table->foreign('ingredient_section_id')->references('id')->on('recipe_ingredient_sections');

            $table->integer('ingredient_id')->unsigned();
            $table->foreign('ingredient_id')->references('id')->on('recipe_ingredients');

            $table->integer('ingredient_substitute_id')->unsigned()->nullable();
            $table->foreign('ingredient_substitute_id')->references('id')->on('recipe_ingredients');

            $table->integer('grams');
            $table->decimal('quantity', 5, 2);
            $table->decimal('quantity_endrange', 5, 2)->nullable();
            $table->string('quantity_unit');

            $table->string('extras')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipe_ingredient_section_ingredients');
    }
}
