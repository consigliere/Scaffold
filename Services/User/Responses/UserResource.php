<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/19/19 2:51 PM
 */

/**
 * UserResource.php
 * Created by @anonymoussc on 05/09/2019 7:25 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

/**
 * Class UserResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
class UserResource
{
    /**
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($data, array $option = [], array $param = [])
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
                    'author'    => $param['api.authors'],
                ];
            }
        }

        return $user;
    }
}