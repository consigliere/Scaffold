<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/4/19 1:31 AM
 */

/**
 * UpdateUserResponse.php
 * Created by @anonymoussc on 04/26/2019 1:56 AM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

/**
 * Class UpdateUserResponse
 * @package App\Components\Scaffold\Services\User\Responses
 */
class UpdateUserResponse
{
    /**
     * @param       $uuid
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($uuid, $data, array $option = [], array $param = [])
    {
        $user = [];

        if (!empty($data)) {
            $user['data'] = [
                'type'       => $param['type'],
                'id'         => $data->uuid,
                'attributes' => [
                    'username' => $data->username,
                    'name'     => $data->name,
                    'email'    => $data->email,
                    'avatar'   => $data->avatar,
                    'settings' => $data->settings,
                ],
            ];

            if ($option['api.hasLink']) {
                $user['link'] = [
                    'self' => $param['link.fullUrl'],
                ];
            }

            if ($option['api.hasMeta']) {
                $user['meta'] = [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $param['app.name'],
                    'author'    => $param['api.meta.author'],
                ];
            }
        }

        return $user;
    }
}