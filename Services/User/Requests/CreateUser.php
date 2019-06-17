<?php
/**
 * CreateUser.php
 * Created by @anonymoussc on 05/09/2019 7:20 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/17/19 2:46 PM
 */

namespace App\Components\Scaffold\Services\User\Requests;

use Webpatser\Uuid\Uuid;

/**
 * Class CreateUser
 * @package App\Components\Scaffold\Services\User\Requests
 */
class CreateUser
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
                'uuid'              => (string)Uuid::generate(5, $dataIn['username'], Uuid::NS_DNS),
                'username'          => strtolower($dataIn['username']),
                'name'              => $dataIn['name'],
                'email'             => $dataIn['email'],
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