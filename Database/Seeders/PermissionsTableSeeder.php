<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/16/19 8:11 AM
 */

use App\Components\Scaffold\Entities\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'uuid'       => randomUuid(),
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');
    }
}
