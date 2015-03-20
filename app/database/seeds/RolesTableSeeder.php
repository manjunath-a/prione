<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->save();

        $CatalogueRole = new Role;
        $CatalogueRole->name = 'Catalogue Manager';
        $CatalogueRole->save();

        $CatalogueTeamLeadRole = new Role;
        $CatalogueTeamLeadRole->name = 'Catalogue Team Lead';
        $CatalogueTeamLeadRole->save();

        $Cataloguer = new Role;
        $Cataloguer->name = 'Cataloguer';
        $Cataloguer->save();

        $Editing = new Role;
        $Editing->name = 'Editing Manager';
        $Editing->save();

        $EditingTeamLeadRole = new Role;
        $EditingTeamLeadRole->name = 'Editing Team Lead';
        $EditingTeamLeadRole->save();

        $Editor = new Role;
        $Editor->name = 'Editor';
        $Editor->save();

        $localTeamLeadRole = new Role;
        $localTeamLeadRole->name = 'Local Team Lead';
        $localTeamLeadRole->save();

        $Photographer = new Role;
        $Photographer->name = 'Photographer';
        $Photographer->save();

        $Services = new Role;
        $Services->name = 'Services Associate';
        $Services->save();

        $user = User::where('username','=','admin')->first();
        $user->attachRole( $adminRole );

        $user = User::where('username','=','CatalogueManager')->first();
        $user->attachRole( $CatalogueRole );

        $user = User::where('username','=','CatalogueTeamLead')->first();
        $user->attachRole( $CatalogueTeamLeadRole );

        $user = User::where('username','=','Cataloguer')->first();
        $user->attachRole( $Cataloguer );

        $user = User::where('username','=','EditingManager')->first();
        $user->attachRole( $Editing );

        $user = User::where('username','=','EditingTeamLead')->first();
        $user->attachRole( $EditingTeamLeadRole );

        $user = User::where('username','=','Editor')->first();
        $user->attachRole( $Editor );

        $user = User::where('username','=','LocalTeamLead')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','Photographer')->first();
        $user->attachRole( $Photographer );

        $user = User::where('username','=','ServicesAssociate')->first();
        $user->attachRole( $Services );
    }

}
