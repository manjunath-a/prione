<?php

class CategoryTableSeeder extends Seeder {

    public function run()
    {
        DB::table('category')->delete();


        $categories= array(
            array(
                'category_name'      => 'Books',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'category_name'      => 'Movies',
                'status'      => 1,
                'sort'  => 2
            ),
            array(
                'category_name'      => 'Mobiles',
                'status'      => 1,
                'sort'  => 3
            ),
            array(
                'category_name'      => 'Home & Kitchen',
                'status'      => 1,
                'sort'  => 4
            ),
            array(
                'category_name'      => 'Handbags',
                'status'      => 1,
                'sort'  => 5
            ),

        );

        DB::table('category')->insert( $categories );
    }

}
