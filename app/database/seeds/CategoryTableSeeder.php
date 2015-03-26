<?php

class CategoryTableSeeder extends Seeder {

    public function run()
    {
        DB::table('category')->delete();


        $categories= array(
            array(
                'category_name'      => 'Apparel',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'category_name'      => 'Automotive',
                'status'=> 1,
                'sort'  => 2
            ),
            array(
                'category_name'      => 'Beauty & Health',
                'status'=> 1,
                'sort'  => 3
            ),
            array(
                'category_name'      => 'Cameras, Audio & Video',
                'status'=> 1,
                'sort'  => 4
            ),
            array(
                'category_name'      => 'Clothing Accessories',
                'status'=> 1,
                'sort'  => 5
            ),
            array(
                'category_name'      => 'Computers & Laptops',
                'status'=> 1,
                'sort'  => 6
            ),
            array(
                'category_name'      => 'CE Accessories',
                'status'=> 1,
                'sort'  => 7
            ),
            array(
                'category_name'      => 'Gourmet & Grocery',
                'status'=> 1,
                'sort'  => 8
            ),
            array(
                'category_name'      => 'Handbags',
                'status'=> 1,
                'sort'  => 9
            ),
            array(
                'category_name'      => 'Home & Kitchen',
                'status'=> 1,
                'sort'  => 10
            ),
            array(
                'category_name'      => 'Jewellery',
                'status'=> 1,
                'sort'  => 11
            ),
            array(
                'category_name'      => 'Luggage',
                'status'=> 1,
                'sort'  => 12
            ),
            array(
                'category_name'      => 'Mobiles & Tablets',
                'status'=> 1,
                'sort'  => 13
            ),
            array(
                'category_name'      => 'Sports, Fitness & Outdoors',
                'status'=> 1,
                'sort'  => 14
            ),
            array(
                'category_name'      => 'Toys & Baby Products',
                'status'=> 1,
                'sort'  => 15
            ),
            array(
                'category_name'      => 'Watches',
                'status'=> 1,
                'sort'  => 16
            ),
            array(
                'category_name'      => 'Shoes',
                'status'=> 1,
                'sort'  => 17
            ),
            array(
                'category_name'      => 'Sunglasses',
                'status'=> 1,
                'sort'  => 18
            ),
            array(
                'category_name'      => 'Others',
                'status'=> 1,
                'sort'  => 19
            ),
        );

        DB::table('category')->insert( $categories );
    }

}
