<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/3/19 7:33 PM
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
        $dataIn = $data['input'];
        $user   = [];
        $inList = [1, 2];
        $roleId = (int)$dataIn['roleId'];

        if (!empty($dataIn)) {
            $user = [
                'role_id'           => in_array($roleId, $inList, true) ? $roleId : 2,
                'uuid'              => (string)Uuid::generate(5, $dataIn['username'], Uuid::NS_DNS),
                'username'          => strtolower($dataIn['username']),
                'name'              => $dataIn['name'],
                'email'             => strtolower($dataIn['email']),
                'avatar'            => $dataIn['avatar'],
                'email_verified_at' => $dataIn['emailVerifiedAt'],
                'password'          => password_hash($dataIn['password'], PASSWORD_BCRYPT),
                'remember_token'    => $dataIn['rememberToken'],
                'settings'          => $dataIn['settings'],
            ];
        }

        return $user;
    }
}