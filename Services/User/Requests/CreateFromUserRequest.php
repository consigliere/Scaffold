<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/27/19 5:17 AM
 */

/**
 * CreateFromUserRequest.php
 * Created by @anonymoussc on 04/24/2019 4:59 PM.
 */

namespace App\Components\Scaffold\Services\User\Requests;

use Webpatser\Uuid\Uuid;

class CreateFromUserRequest
{
    public function __invoke(array $data = [], array $option = [], array $param = [])
    {
        $user = [];

        if (!empty($data)) {
            $user = [
                'role_id'           => ($data['roleId'] === 1) ? $data['roleId'] : 2,
                'uuid'              => (string)Uuid::generate(5, $data['username'], Uuid::NS_DNS),
                'username'          => strtolower($data['username']),
                'name'              => $data['name'],
                'email'             => $data['email'],
                'avatar'            => $data['avatar'],
                'email_verified_at' => $data['emailVerifiedAt'],
                'password'          => password_hash($data['password'], PASSWORD_BCRYPT),
                'remember_token'    => $data['rememberToken'],
                'settings'          => $data['settings'],
            ];
        }

        return $user;
    }
}