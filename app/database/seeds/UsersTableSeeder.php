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
                'city_id'   => City::where('city_name', 'Chennai')->first()->id,
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
                'username'      => 'LocalTeamLead',
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
            )
        );
        DB::table('users')->insert( $users );
    }

}
