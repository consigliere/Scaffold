<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/22/19 8:34 AM
 */

/**
 * User.php
 * Created by @anonymoussc on 04/06/2019 12:32 PM.
 */

namespace App\Components\Scaffold\Entities;

use App\User as AppUser;

/**
 * Class User
 * @package App\Components\Passerby\Entities
 */
class User extends AppUser
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->uuid = (string)\Webpatser\Uuid\Uuid::generate(4);
        });
    }
}
