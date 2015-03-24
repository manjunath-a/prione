<?php

class SalesChannelTableSeeder extends Seeder {

    public function run()
    {
        DB::table('sales_channel')->delete();


        $salesChannel= array(
            array(
                'channel_name'      => 'DS',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'channel_name'      => 'SSR',
                'status'      => 1,
                'sort'  => 2
            ),
            array(
                'channel_name'      => 'RA',
                'status'      => 1,
                'sort'  => 3
            ),
            array(
                'channel_name'      => 'Prione',
                'status'      => 1,
                'sort'  => 4
            ),
        );

        DB::table('sales_channel')->insert( $salesChannel );
    }

}
