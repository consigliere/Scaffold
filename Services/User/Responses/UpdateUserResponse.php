<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/1/19 3:57 AM
 */

/**
 * UpdateUserResponse.php
 * Created by @anonymoussc on 04/26/2019 1:56 AM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\Config;

class UpdateUserResponse
{
    public function __invoke($uuid, $data, array $option = [], array $param = [])
    {
        $user = [];

        if (!empty($data)) {
            $user = [
                'data' => [
                    'type'       => 'users',
                    'id'         => $uuid,
                    'attributes' => [
                        'username' => $data->username,
                        'name'     => $data->name,
                        'email'    => $data->email,
                    ],
                ],
                'link' => [
                    'self' => $param['link']['fullUrl'],
                ],
                'meta' => [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . Config::get('app.name'),
                    'author'    => Config::get('scaffold.api.meta.author'),
                    // 'email'     => Config::get('scaffold.api.meta.email'),
                ],

            ];
        }

        return $user;
    }
}