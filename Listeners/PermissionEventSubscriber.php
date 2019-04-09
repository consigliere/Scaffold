<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:24 PM
 */

/**
 * PermissionEventSubscriber.php
 * Created by @anonymoussc on 04/09/2019 12:07 PM.
 */

namespace App\Components\Scaffold\Listeners;

/**
 * Class PermissionEventSubscriber
 * @package App\Components\Scaffold\Listeners
 */
class PermissionEventSubscriber
{
    public function subscribe($events): void
    {
        $events->listen('permission.message', 'App\Components\Scaffold\Listeners\PermissionEventSubscriber@onPermissionCreate', 10);
    }

    public function onPermissionCreate(array $user): void
    {
        //
    }
}