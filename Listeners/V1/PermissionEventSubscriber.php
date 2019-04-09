<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:22 PM
 */

/**
 * PermissionEventSubscriber.php
 * Created by @anonymoussc on 04/09/2019 12:07 PM.
 */

namespace App\Components\Scaffold\Listeners\V1;

/**
 * Class PermissionEventSubscriber
 * @package App\Components\Scaffold\Listeners\V1
 */
class PermissionEventSubscriber extends \App\Components\Scaffold\Listeners\PermissionEventSubscriber
{
    public function subscribe($events): void
    {
        $events->listen('permission.v1.create', 'App\Components\Scaffold\Listeners\V1\PermissionEventSubscriber@onPermissionCreate', 10);
    }

    public function onPermissionCreate(array $user): void
    {
        //
    }
}