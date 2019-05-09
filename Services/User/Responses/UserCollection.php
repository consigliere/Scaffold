<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/9/19 7:28 PM
 */

/**
 * UserCollection.php
 * Created by @anonymoussc on 05/09/2019 6:44 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

/**
 * Class UserCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
class UserCollection
{
    /**
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function __invoke($data, array $option = [], array $param = [])
    {
        $newData = [];

        if (!empty($data)) {
            $newData = $data->map(function($value, $key) use ($param) {
                return [
                    'type'       => $param['type'],
                    'id'         => $value->uuid,
                    'attributes' => [
                        'username' => $value->username,
                        'name'     => $value->name,
                        'email'    => $value->email,
                        'avatar'   => $value->avatar,
                        'settings' => $value->settings,
                    ],
                ];
            });
        }

        $users['data'] = $newData;
        $users['link'] = [
            'self'  => $param['link.fullUrl'],
            'first' => $data->url(1),
            'last'  => $data->url($data->lastPage()),
            'prev'  => $data->previousPageUrl(),
            'next'  => $data->nextPageUrl(),
        ];
        $users['meta'] = [
            "current_page" => $data->currentPage(),
            'from'         => $data->firstItem(),
            'last_page'    => $data->lastPage(),
            'path'         => $param['link.url'],
            'per_page'     => $data->perPage(),
            'to'           => $data->lastItem(),
            'total'        => $data->total(),
            'copyright'    => 'copyrightⒸ ' . date('Y') . ' ' . $param['app.name'],
            'author'       => $param['api.meta.author'],
        ];

        return $users;
    }
}