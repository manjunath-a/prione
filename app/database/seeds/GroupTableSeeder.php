<?php

class GroupTableSeeder extends Seeder {

    public function run()
    {
        DB::table('group')->delete();


        $groups = array (
            array(
                'group_name'      => 'Local',
                'status'      => 1,
                'sort'  => 1
            ),
            array(
                'group_name'      => 'Editing',
                'status'      => 1,
                'sort'  => 2
            ),
            array(
                'group_name'      => 'Cataloging',
                'status'      => 1,
                'sort'  => 3
            ),
        );

        DB::table('group')->insert( $groups );
    }

}
