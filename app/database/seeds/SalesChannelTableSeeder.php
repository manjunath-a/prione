<?php

class SalesChannelTableSeeder extends Seeder {

    public function run()
    {
        DB::table('sales_channel')->delete();


        $salesChannel= array(
            array(
                'channel_name'      => 'Email',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'channel_name'      => 'Portal',
                'status'      => 1,
                'sort'  => 2
            ),
            array(
                'channel_name'      => 'Phone',
                'status'      => 1,
                'sort'  => 3
            ),
            array(
                'channel_name'      => 'Forum',
                'status'      => 1,
                'sort'  => 4
            ),
            array(
                'channel_name'      => 'Twitter',
                'status'      => 1,
                'sort'  => 5
            ),
            array(
                'channel_name'      => 'Facebook',
                'status'      => 1,
                'sort'  => 6
            ),
            array(
                'channel_name'      => 'Chat',
                'status'      => 1,
                'sort'  => 7
            ),
            array(
                'channel_name'      => 'MobiHelp',
                'status'      => 1,
                'sort'  => 8
            ),
            array(
                'channel_name'      => 'Facebookwidget',
                'status'      => 1,
                'sort'  => 9
            ),

        );

        DB::table('sales_channel')->insert( $salesChannel );
    }

}
