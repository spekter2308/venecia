<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE DEFINER =  `root`@`%` TRIGGER `categories_insert` BEFORE INSERT ON  `categories` 
            FOR EACH
            ROW IF NEW.parent_id IS NULL 
            THEN 
            SET NEW.sequence = ( SELECT COUNT( * ) 
            FROM  `categories` 
            WHERE parent_id IS NULL ) +1;
            
            END IF
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `categories_insert`');
    }
}
