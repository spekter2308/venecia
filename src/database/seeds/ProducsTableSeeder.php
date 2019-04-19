<?php

use Illuminate\Database\Seeder;

class ProducsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<30;$i++) {
            DB::table('products')->insert([
                'product_name' => str_random(40),
                'price' => rand(1, 100),
                'product_sku'=>rand(1, 10000),
                'cat_id'=>2,
                'brand_id'=>1,
                'featured'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }


    }
}
