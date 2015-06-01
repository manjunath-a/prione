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
                'pending_reason'    => 'Seller not giving appointment',
                'status'            => 1,
                'sort'              => 2
            ),
            array(
                'pending_reason'    => 'Seller not providing data for building MIF',
                'status'            => 1,
                'sort'              => 3
            ),
            array(
                'pending_reason'    => 'Raw Images QC failed',
                'status'            => 1,
                'sort'              => 4
            ),
            array(
                'pending_reason'    => 'MIF QC failed',
                'status'            => 1,
                'sort'              => 5
            )
        );
        DB::table('pending_reason')->insert( $pendingReason );
    }

}
