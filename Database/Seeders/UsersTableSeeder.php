<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/16/19 8:14 AM
 */

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert([
            0 =>
                [
                    'uuid'              => randomUuid(),
                    'username'          => 'user',
                    'name'              => 'user',
                    'email'             => 'user@api.com',
                    'email_verified_at' => null,
                    'password'          => password_hash('user', PASSWORD_BCRYPT),
                    'remember_token'    => null,
                    'created_at'        => null,
                    'updated_at'        => null,
                ],
        ]);


    }
}