<?php
/**
 * UpdateUser.php
 * Created by @anonymoussc on 05/09/2019 7:20 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 6:15 AM
 */

namespace App\Components\Scaffold\Services\User\Requests;

/**
 * Class UpdateUser
 * @package App\Components\Scaffold\Services\User\Requests
 */
final class UpdateUser
{
    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke(array $data = [], array $option = [], array $param = [])
    {
        $dataIn = $data['input'];
        $user   = [];
        $inList = $data['inList'];
        $roleId = (int)$dataIn['roleId'];

        if (!empty($dataIn)) {
            $user = [
                'role_id'           => in_array($roleId, $inList, true) ? $roleId : 2,
                'username'          => $dataIn['username'] ? strtolower($dataIn['username']) : null,
                'name'              => $dataIn['name'],
                'email'             => $dataIn['email'] ?? null,
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