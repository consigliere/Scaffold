<?php
/**
 * Role.php
 * Created by @anonymoussc on 04/09/2019 12:58 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/1/19 2:23 AM
 */

namespace App\Components\Scaffold\Entities;

use App\Components\Signature\Traits\UuidsTrait;
use TCG\Voyager\Models\Role as BaseRole;

/**
 * Class Role
 * @package App\Components\Scaffold\Entities
 * @property mixed $permissions
 */
class Role extends BaseRole
{
    use UuidsTrait;

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Components\Scaffold\Entities\Permission', 'permission_role', 'role_id', 'permission_id');
    }
}
