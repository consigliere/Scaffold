<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:23 PM
 */

/**
 * UserEventSubscriber.php
 * Created by @anonymoussc on 04/09/2019 12:06 PM.
 */

namespace App\Components\Scaffold\Listeners\V1;

/**
 * Class UserEventSubscriber
 * @package App\Components\Scaffold\Listeners\V1
 */
class UserEventSubscriber extends \App\Components\Scaffold\Listeners\UserEventSubscriber
{
    public function subscribe($events): void
    {
        $events->listen('user.v1.create', 'App\Components\Scaffold\Listeners\V1\UserEventSubscriber@onUserCreate', 10);
    }

    public function onUserCreate(array $user): void
    {
        //
    }
}