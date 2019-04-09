<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:25 PM
 */

/**
 * UserEventSubscriber.php
 * Created by @anonymoussc on 04/09/2019 12:06 PM.
 */

namespace App\Components\Scaffold\Listeners;

/**
 * Class UserEventSubscriber
 * @package App\Components\Scaffold\Listeners
 */
class UserEventSubscriber
{
    public function subscribe($events): void
    {
        $events->listen('user.create', 'App\Components\Scaffold\Listeners\UserEventSubscriber@onUserCreate', 10);
    }

    public function onUserCreate(array $user): void
    {
        //
    }
}