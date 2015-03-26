<?php

class CityTableSeeder extends Seeder {

    public function run()
    {
        DB::table('city')->delete();


        $citys = array(
            array(
                'city_name'      => 'Bangalore',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'city_name'      => 'Chennai',
                'status'      => 1,
                'sort'  => 2
            ),
            array(
                'city_name'      => 'Mumbai',
                'status'      => 1,
                'sort'  => 3
            ),
            array(
                'city_name'      => 'Delhi',
                'status'      => 1,
                'sort'  => 4
            ),
            array(
                'city_name'      => 'Ahmedabad',
                'status'      => 1,
                'sort'  => 5
            ),
            array(
                'city_name'      => 'Coimbatore',
                'status'      => 1,
                'sort'  => 6
            ),
            array(
                'city_name'      => 'Hyderabad',
                'status'      => 1,
                'sort'  => 7
            ),
            array(
                'city_name'      => 'Jaipur',
                'status'      => 1,
                'sort'  => 8
            ),
            array(
                'city_name'      => 'Vijayawada',
                'status'      => 1,
                'sort'  => 9
            ),
            array(
                'city_name'      => 'Kochi',
                'status'      => 1,
                'sort'  => 10
            ),
            array(
                'city_name'      => 'Kolkata',
                'status'      => 1,
                'sort'  => 11
            ),
            array(
                'city_name'      => 'Lucknow',
                'status'      => 1,
                'sort'  => 12
            ),
            array(
                'city_name'      => 'Ludhiana',
                'status'      => 1,
                'sort'  => 13
            ),
            array(
                'city_name'      => 'Panipat',
                'status'      => 1,
                'sort'  => 14
            ),
            array(
                'city_name'      => 'Pune',
                'status'      => 1,
                'sort'  => 15
            ),
            array(
                'city_name'      => 'Surat',
                'status'      => 1,
                'sort'  => 16
            ),
            array(
                'city_name'      => 'Jodhpur',
                'status'      => 1,
                'sort'  => 17
            ),
        );

        DB::table('city')->insert( $citys );
    }

}
