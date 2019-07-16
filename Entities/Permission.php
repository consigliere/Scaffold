<?php
/**
 * Permission.php
 * Created by @anonymoussc on 04/09/2019 12:58 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/16/19 7:25 AM
 */

namespace App\Components\Scaffold\Entities;

use App\Components\Signature\Traits\UuidsTrait;
use TCG\Voyager\Models\Permission as AppPermission;

class Permission extends AppPermission
{
    use UuidsTrait;

    public static function generateFor($table_name)
    {
        self::firstOrCreate(['uuid' => randomUuid(), 'key' => 'browse_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['uuid' => randomUuid(), 'key' => 'read_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['uuid' => randomUuid(), 'key' => 'edit_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['uuid' => randomUuid(), 'key' => 'add_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['uuid' => randomUuid(), 'key' => 'delete_' . $table_name, 'table_name' => $table_name]);
    }

    public static function removeFrom($table_name)
    {
        self::where(['table_name' => $table_name])->delete();
    }
}
