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
        );

        DB::table('city')->insert( $citys );
    }

}
