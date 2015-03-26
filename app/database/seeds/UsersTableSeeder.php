<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();


        $users = array(
            array(
                'username'      => 'admin',
                'email'      => 'prioneadmin@mailinator.com',
                'password'   => Hash::make('admin'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'CatalogueManager',
                'email'      => 'CatalogueManager@mailinator.com',
                'password'   => Hash::make('user'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'CatalogueTeamLead',
                'email'      => 'CatalogueTeamLead@mailinator.com',
                'password'   => Hash::make('CatalogueTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'Cataloguer',
                'email'      => 'Cataloguer@mailinator.com',
                'password'   => Hash::make('Cataloguer'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'EditingManager',
                'email'      => 'EditingManager@mailinator.com',
                'password'   => Hash::make('EditingManager'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'EditingTeamLead',
                'email'      => 'EditingTeamLead@mailinator.com',
                'password'   => Hash::make('EditingTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'Editor',
                'email'      => 'Editor@mailinator.com',
                'password'   => Hash::make('Editor'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadBangalore',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'Photographer',
                'email'      => 'Photographer@mailinator.com',
                'password'   => Hash::make('Photographer'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'ServicesAssociate',
                'email'      => 'ServicesAssociate@mailinator.com',
                'password'   => Hash::make('ServicesAssociate'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Bangalore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadChennai',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Chennai')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadMumbai',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Mumbai')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadDelhi',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Delhi')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadAhmedabad',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Ahmedabad')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadCoimbatore',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Coimbatore')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadHyderabad',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Hyderabad')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadJaipur',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Jaipur')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadVijayawada',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Vijayawada')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadKochi',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Kochi')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadKolkata',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Kolkata')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadLucknow',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Lucknow')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadLudhiana',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Ludhiana')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadPanipat',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Panipat')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadPune',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Pune')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadSurat',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Surat')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'LocalTeamLeadJodhpur',
                'email'      => 'LocalTeamLead@mailinator.com',
                'password'   => Hash::make('LocalTeamLead'),
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'city_id'   => City::where('city_name', 'Jodhpur')->first()->id,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            )
        );
        DB::table('users')->insert( $users );
    }

}
