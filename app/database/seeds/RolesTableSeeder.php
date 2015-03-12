<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->save();

        $cityLeadRole = new Role;
        $cityLeadRole->name = 'citylead';
        $cityLeadRole->save();

        $user = User::where('username','=','admin')->first();
        $user->attachRole( $adminRole );

        $user = User::where('username','=','user')->first();
        $user->attachRole( $cityLeadRole );
    }

}
