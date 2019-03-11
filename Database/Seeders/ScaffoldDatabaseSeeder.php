<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 3/11/19 7:21 PM
 */

/**
 * ScaffoldDatabaseSeeder.php
 * Created by @anonymoussc on 03/11/2019 7:30 PM.
 */

namespace App\Components\Scaffold\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ScaffoldDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
