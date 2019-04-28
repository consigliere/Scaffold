<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/27/19 5:17 AM
 */

/**
 * UpdateFromUserRequest.php
 * Created by @anonymoussc on 04/26/2019 1:56 AM.
 */

namespace App\Components\Scaffold\Services\User\Requests;

class UpdateFromUserRequest
{
    public function __invoke($uuid, array $data = [], array $option = [], array $param = [])
    {
        $user = [];

        if (!empty($data)) {
            $user = [
                'role_id'           => $data['roleId'],
                'username'          => strtolower($data['username']),
                'name'              => $data['name'],
                'email'             => $data['email'],
                'avatar'            => $data['avatar'],
                'email_verified_at' => $data['emailVerifiedAt'],
                'remember_token'    => $data['rememberToken'],
                'settings'          => $data['settings'],
            ];

            if (isset($data['password']) && !empty($data['password']) && ($data['password'] !== null)) {
                $user['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
        }

        return $user;
    }
}