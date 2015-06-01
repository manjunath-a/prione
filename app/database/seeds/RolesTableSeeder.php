<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->save();

        $CatalogueTeamLeadRole = new Role;
        $CatalogueTeamLeadRole->name = 'Catalog Team Lead';
        $CatalogueTeamLeadRole->save();

        $Cataloguer = new Role;
        $Cataloguer->name = 'Cataloger';
        $Cataloguer->save();

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

        $user = User::where('username','=','CatalogueTeamLead')->first();
        $user->attachRole( $CatalogueTeamLeadRole );

        $user = User::where('username','=','Cataloguer')->first();
        $user->attachRole( $Cataloguer );

        $user = User::where('username','=','EditingTeamLead')->first();
        $user->attachRole( $EditingTeamLeadRole );

        $user = User::where('username','=','Editor')->first();
        $user->attachRole( $Editor );

        $user = User::where('username','=','LocalTeamLeadBangalore')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadChennai')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadMumbai')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadDelhi')->first();
        $user->attachRole( $localTeamLeadRole );


        $user = User::where('username','=','LocalTeamLeadAhmedabad')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadCoimbatore')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadHyderabad')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadJaipur')->first();
        $user->attachRole( $localTeamLeadRole );


        $user = User::where('username','=','LocalTeamLeadVijayawada')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadKochi')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadKolkata')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadLucknow')->first();
        $user->attachRole( $localTeamLeadRole );


        $user = User::where('username','=','LocalTeamLeadLudhiana')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadPanipat')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadPune')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadSurat')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','LocalTeamLeadJodhpur')->first();
        $user->attachRole( $localTeamLeadRole );

        $user = User::where('username','=','Photographer')->first();
        $user->attachRole( $Photographer );

        $user = User::where('username','=','ServicesAssociate')->first();
        $user->attachRole( $Services );
    }

}
