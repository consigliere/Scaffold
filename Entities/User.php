<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/14/19 8:24 AM
 */

/**
 * User.php
 * Created by @anonymoussc on 04/06/2019 12:32 PM.
 */

namespace App\Components\Scaffold\Entities;

use App\User as AppUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Components\Passerby\Entities
 */
class User extends AppUser
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'uuid',
        'username',
        'name',
        'email',
        'avatar',
        'email_verified_at',
        'password',
        'settings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];
}
