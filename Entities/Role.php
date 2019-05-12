<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/12/19 7:22 AM
 */

/**
 * Role.php
 * Created by @anonymoussc on 04/09/2019 12:58 PM.
 */

namespace App\Components\Scaffold\Entities;

use App\Components\Signature\Traits\UuidsTrait;
use TCG\Voyager\Models\Role as BaseRole;

/**
 * Class Role
 * @package App\Components\Scaffold\Entities
 */
class Role extends BaseRole
{
    use UuidsTrait;
}
