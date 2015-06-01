<?php

class RejectionReasonTableSeeder extends Seeder {

    public function run()
    {
        DB::table('rejection_reason')->delete();


        $rejectionReason = array(
            array(
                'rejection_reason'  => 'Raw Images QC failed',
                'status'            => 1,
                'sort'              => 1
            ),
            array(
                'rejection_reason'  => 'Editied Images QC failed',
                'status'            => 1,
                'sort'              => 2
            ),
            array(
                'rejection_reason'  => 'MIF images mapping incorrect',
                'status'            => 1,
                'sort'              => 3
            ),
            array(
                'rejection_reason'  => 'Images or MIF not available',
                'status'            => 1,
                'sort'              => 4
            ),
            array(
                'pending_reason'    => 'MIF QC failed',
                'status'            => 1,
                'sort'              => 5
            ),
            array(
                'pending_reason'    => 'Flat File QC failed',
                'status'            => 1,
                'sort'              => 6
            )
        );
        DB::table('rejection_reason')->insert( $rejectionReason );
    }

}
