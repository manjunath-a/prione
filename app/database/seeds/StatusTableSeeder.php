<?php

class StatusTableSeeder extends Seeder {

    public function run()
    {
        DB::table('status')->delete();


        $status = array(
            array(
                'status_name'      => 'Open',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'status_name'      => 'Pending',
                'status'      => 1,
                'sort'  => 2
            ),
            array(
                'status_name'      => 'Resolved',
                'status'      => 1,
                'sort'  => 3
            ),
            array(
                'status_name'      => 'Closed',
                'status'      => 1,
                'sort'  => 4
            ),
            array(
                'status_name'      => 'Rejectd',
                'status'      => 1,
                'sort'  => 5
            ),
        );

        DB::table('status')->insert( $status );
    }

}
