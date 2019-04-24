<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 2:04 AM
 */

/**
 * UserCreateDataResponse.php
 * Created by @anonymoussc on 04/24/2019 5:42 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\Config;

class UserCreateDataResponse
{
    public function __invoke($data, array $option = [], array $param = [])
    {
        $user = [];

        if (!empty($data)) {
            $user = [
                'data' => [
                    'type'       => 'users',
                    'id'         => $data->id,
                    'attributes' => [
                        'uuid'     => $data->uuid,
                        'username' => $data->username,
                        'name'     => $data->name,
                        'email'    => $data->email,
                    ],

                ],
                'meta' => [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . Config::get('app.name'),
                    'author'    => Config::get('scaffold.api.meta.author'),
                    'email'     => Config::get('scaffold.api.meta.email'),
                ],
                'link' => [
                    'self' => $param['self']['link'],
                ],

            ];

            /*$user['username'] = $data->username;
            $user['name']     = $data->name;
            $user['email']    = $data->email;*/
        }

        return $user;
    }
}