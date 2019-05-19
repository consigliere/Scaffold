<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/19/19 2:51 PM
 */

/**
 * PermissionResource.php
 * Created by @anonymoussc on 05/12/2019 9:27 AM.
 */

namespace App\Components\Scaffold\Services\Permission\Responses;

/**
 * Class PermissionResource
 * @package App\Components\Scaffold\Services\Permission\Responses
 */
class PermissionResource
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
                    'key'    => $data->key,
                    'entity' => $data->table_name,
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