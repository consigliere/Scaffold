<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:10 PM
 */

/**
 * User.php
 * Created by @anonymoussc on 04/06/2019 12:32 PM.
 */

namespace App\Components\Passerby\Entities;

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
