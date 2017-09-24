<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->delete();


        DB::table('users')->insert(array(
            0 => array(
                'id' => 1,
                'name' => 'Johnson',
                'email' => 'phpjourney@johnson.com',
                'password' => '$2y$10$mHewdI8KU9EtclzzEwICZe2wf6qoFK6aaB5CFOvHx7BfetFdTKNjm',
                'remember_token' => 'tDpcjqLYBeWU11ULTVcmsIFaaSiqdvVh8zDeNbFZ29lhqQNUR3Ki0QtEzCNd',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        ));
    }
}
