<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/2/19 12:31 AM
 */

/**
 * BrowseUserResponse.php
 * Created by @anonymoussc on 04/30/2019 4:55 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\Config;

/**
 * Class BrowseUserResponse
 * @package App\Components\Scaffold\Services\User\Responses
 */
class BrowseUserResponse
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
            $newData = $data->map(function($value, $key) {
                return [
                    'type'       => 'users',
                    'id'         => $value->uuid,
                    'attributes' => [
                        'username' => $value->username,
                        'name'     => $value->name,
                        'email'    => $value->email,
                    ],
                ];
            });
        }

        $users['data'] = $newData;
        $users['link'] = [
            'self'  => $param['link']['fullUrl'],
            'first' => $data->url(1),
            'last'  => $data->url($data->lastPage()),
            'prev'  => $data->previousPageUrl(),
            'next'  => $data->nextPageUrl(),
        ];
        $users['meta'] = [
            "current_page" => $data->currentPage(),
            'from'         => $data->firstItem(),
            'last_page'    => $data->lastPage(),
            'path'         => $param['link']['url'],
            'per_page'     => $data->perPage(),
            'to'           => $data->lastItem(),
            'total'        => $data->total(),
            'copyright'    => 'copyrightⒸ ' . date('Y') . ' ' . Config::get('app.name'),
            'author'       => Config::get('scaffold.api.meta.author'),
            // 'email'     => Config::get('scaffold.api.meta.email'),
        ];

        return $users;
    }
}