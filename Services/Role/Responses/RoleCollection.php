<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/11/19 2:31 PM
 */

/**
 * RoleCollection.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

namespace App\Components\Scaffold\Services\Role\Responses;

/**
 * Class RoleCollection
 * @package App\Components\Scaffold\Services\Role\Responses
 */
class RoleCollection
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
                        'name'        => $value->name,
                        'displayName' => $value->display_name,
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