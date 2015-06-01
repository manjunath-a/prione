<?php

class StageTableSeeder extends Seeder {

    public function run()
    {
        DB::table('stage')->delete();


        $stages = array(
            array(
                'stage_name'  => '(Local) Associates Not Assigned',
                'status'      => 1,
                'sort'        => 1
            ),
            array(
                'stage_name'  => '(Local) Associates Assigned',
                'status'      => 1,
                'sort'        => 2
            ),
            array(
                'stage_name'  => '(Local) Photoshoot Completed',
                'status'      => 1,
                'sort'        => 3
            ),
            array(
                'stage_name'  => '(Local) MIF Completed',
                'status'      => 1,
                'sort'        => 4
            ),
            array(
                'stage_name'  => '(Local) QC Completed',
                'status'      => 1,
                'sort'        => 5
            ),
            array(
                'stage_name'  => '(Central) Editing Completed',
                'status'      => 1,
                'sort'        => 6
            ),
            array(
                'stage_name'  => '(Central) Edited Images QC Completed',
                'status'      => 1,
                'sort'        => 7
            ),
            array(
                'stage_name'  => '(Central) Flat File Creataed',
                'status'      => 1,
                'sort'        => 8
            ),
            array(
                'stage_name'  => '(Central) Flat File QC Completed',
                'status'      => 1,
                'sort'        => 9
            ),
            array(
                'stage_name'  => '(Central) ASIN Created',
                'status'      => 1,
                'sort'        => 10
            ),
        );

        DB::table('stage')->insert( $stages );
    }

}
