<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeedtestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speedtest_results', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('download', 5, 2);
            $table->decimal('upload', 5, 2);
            $table->decimal('ping', 5, 2);
            $table->string('hostname');
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
        Schema::drop('speedtest_results');
    }
}
