<?php
/**
 * User.php
 * Created by @anonymoussc on 04/06/2019 12:32 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/17/19 12:09 AM
 */

namespace App\Components\Scaffold\Entities;

use App\User as AppUser;
use Illuminate\Support\Facades\Auth;

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
            $model->uuid       = (string)\Webpatser\Uuid\Uuid::generate(4);
            $model->created_by = Auth::id() ?? 0;
        });

        static::updating(function($model) {
            $model->updated_by = Auth::id() ?? 0;
        });
    }
}
