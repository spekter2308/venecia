<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesNamesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_names_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('properties_name_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->timestamps();
//            $table->foreign('properties_name_id')
//                ->references('id')->on('properties_names')
//                ->onDelete('cascade');
//            $table->foreign('category_id')
//                ->references('id')->on('categories')
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
        Schema::drop('properties_names_categories');
    }
}
