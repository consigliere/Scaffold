<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/4/19 1:23 AM
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
        $dataIn = $data['input'];
        $user   = [];
        $inList = [1, 2];
        $roleId = (int)$dataIn['roleId'];

        if (!empty($dataIn)) {
            $user = [
                'role_id'           => in_array($roleId, $inList, true) ? $roleId : 2,
                'username'          => strtolower($dataIn['username']),
                'name'              => $dataIn['name'],
                'email'             => strtolower($dataIn['email']),
                'avatar'            => $dataIn['avatar'],
                'email_verified_at' => $dataIn['emailVerifiedAt'],
                'remember_token'    => $dataIn['rememberToken'],
                'settings'          => $dataIn['settings'],
            ];

            if (isset($dataIn['password']) && !empty($dataIn['password']) && ($dataIn['password'] !== null)) {
                $user['password'] = password_hash($dataIn['password'], PASSWORD_BCRYPT);
            }
        }

        return $user;
    }
}