<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/16/19 7:29 AM
 */

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'uuid'         => randomUuid(),
                'display_name' => __('voyager::seeders.roles.admin'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'user']);
        if (!$role->exists) {
            $role->fill([
                'uuid'         => randomUuid(),
                'display_name' => __('voyager::seeders.roles.user'),
            ])->save();
        }
    }
}
