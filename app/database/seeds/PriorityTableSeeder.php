<?php

class PriorityTableSeeder extends Seeder {

    public function run()
    {
        DB::table('priority')->delete();

        $priority = array(
            array(
                'priority_name' => 'Low',
                'status'        => 1,
                'sort'          => 1
            ),
            array(
                'priority_name' => 'Medium',
                'status'        => 1,
                'sort'          => 2
            ),
            array(
                'priority_name'  => 'High',
                'status'         => 1,
                'sort'           => 3
            )
        );

        DB::table('priority')->insert( $priority );
    }

}
