<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('properties_name_id')->unsigned();
            $table->integer('properties_value_id')->unsigned();
            $table->timestamps();
//            $table->foreign('properties_name_id')
//                ->references('id')->on('properties_names')
//                ->onDelete('cascade');
//            $table->foreign('properties_value_id')
//                ->references('id')->on('properties_values')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('properties');
    }
}
