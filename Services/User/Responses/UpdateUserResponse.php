<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/3/19 2:40 PM
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
            $user['data'] = [
                'type'       => $param['type'],
                'id'         => $uuid,
                'attributes' => [
                    'username' => $data->username,
                    'name'     => $data->name,
                    'email'    => $data->email,
                    'avatar'   => $data->avatar,
                    'settings' => $data->settings,
                ],
            ];
            $user['link'] = [
                'self' => $param['link']['fullUrl'],
            ];
            $user['meta'] = [
                'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . Config::get('app.name'),
                'author'    => Config::get('scaffold.api.meta.author'),
            ];
        }

        return $user;
    }
}