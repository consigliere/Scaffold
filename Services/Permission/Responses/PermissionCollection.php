<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/13/19 7:33 AM
 */

/**
 * PermissionCollection.php
 * Created by @anonymoussc on 05/12/2019 9:26 AM.
 */

namespace App\Components\Scaffold\Services\Permission\Responses;

/**
 * Class PermissionCollection
 * @package App\Components\Scaffold\Services\Permission\Responses
 */
class PermissionCollection
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
                        'key'    => $value->key,
                        'entity' => $value->table_name,
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