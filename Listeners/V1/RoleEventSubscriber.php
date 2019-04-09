<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:23 PM
 */

/**
 * RoleEventSubscriber.php
 * Created by @anonymoussc on 04/09/2019 12:07 PM.
 */

namespace App\Components\Scaffold\Listeners\V1;

/**
 * Class RoleEventSubscriber
 * @package App\Components\Scaffold\Listeners\V1
 */
class RoleEventSubscriber extends \App\Components\Scaffold\Listeners\RoleEventSubscriber
{
    public function subscribe($events): void
    {
        $events->listen('role.v1.create', 'App\Components\Scaffold\Listeners\V1\RoleEventSubscriber@onRoleCreate', 10);
    }

    public function onRoleCreate(array $role): void
    {
        //
    }
}