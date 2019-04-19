<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersConfirmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('full_name');
            $table->string('phone');
            $table->string('city');
            $table->string('postDepartment');
            $table->integer('product_id');
            $table->string('product_qty');
            $table->decimal('price',9,2);
            $table->decimal('total_price',9,2);
            $table->boolean('discount_state');
            $table->string('state')->nullable();
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
        Schema::drop('order_products');
    }
}
