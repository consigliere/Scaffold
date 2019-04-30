<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/1/19 3:48 AM
 */

/**
 * BrowseUserResponse.php
 * Created by @anonymoussc on 04/30/2019 4:55 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\Config;

class BrowseUserResponse
{
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

        $users = [
            'data' => $newData,
            'link' => [
                'self'  => $param['link']['fullUrl'],
                'first' => $data->url(1),
                'last'  => $data->url($data->lastPage()),
                'prev'  => $data->previousPageUrl(),
                'next'  => $data->nextPageUrl(),
            ],
            'meta' => [
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
            ],
        ];

        return $users;
    }
}