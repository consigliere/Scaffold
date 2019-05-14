<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/12/19 9:37 AM
 */

/**
 * Permission.php
 * Created by @anonymoussc on 04/09/2019 12:58 PM.
 */

namespace App\Components\Scaffold\Entities;

use App\Components\Signature\Traits\UuidsTrait;
use TCG\Voyager\Models\Permission as AppPermission;

class Permission extends AppPermission
{
    use UuidsTrait;
}
