<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/3/19 2:32 PM
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
        $user   = [];
        $inList = [1, 2];
        $roleId = (int)$data['roleId'];

        if (!empty($data)) {
            $user = [
                'role_id'           => in_array($roleId, $inList, true) ? $roleId : 2,
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