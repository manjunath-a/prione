<?php

class PendingReasonTableSeeder extends Seeder {

    public function run()
    {
        DB::table('pending_reason')->delete();


        $pendingReason = array(
            array(
                'pending_reason'    => 'Seller not reachable',
                'status'            => 1,
                'sort'              => 1
            ),
            array(
                'pending_reason'    => 'Seller not giving appointment / not ready',
                'status'            => 1,
                'sort'              => 2
            ),
            array(
                'pending_reason'    => 'Seller cancelled the appointment',
                'status'            => 1,
                'sort'              => 3
            ),
            array(
                'pending_reason'    => 'Seller not providing data for building MIF',
                'status'            => 1,
                'sort'              => 4
            ),
            array(
                'pending_reason'    => 'Images QC Failed',
                'status'            => 1,
                'sort'              => 5
            ),
            array(
                'pending_reason'    => 'MIF QC Failed',
                'status'            => 1,
                'sort'              => 6
            )
        );

        DB::table('pending_reason')->insert( $pendingReason );
    }

}
