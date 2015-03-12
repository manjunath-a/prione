<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        // Add calls to Seeders here
        $this->call('UsersTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('PermissionsTableSeeder');
        $this->call('CityTableSeeder');
        $this->call('CategoryTableSeeder');
        $this->call('SalesChannelTableSeeder');
        $this->call('StatusTableSeeder');
        $this->call('StageTableSeeder');
    }

}