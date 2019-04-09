<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:24 PM
 */

/**
 * RoleEventSubscriber.php
 * Created by @anonymoussc on 04/09/2019 12:07 PM.
 */

namespace App\Components\Scaffold\Listeners;

/**
 * Class RoleEventSubscriber
 * @package App\Components\Scaffold\Listeners
 */
class RoleEventSubscriber
{
    public function subscribe($events): void
    {
        $events->listen('role.create', 'App\Components\Scaffold\Listeners\RoleEventSubscriber@onRoleCreate', 10);
    }

    public function onRoleCreate(array $role): void
    {
        //
    }
}