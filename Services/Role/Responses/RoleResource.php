<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/11/19 3:00 PM
 */

/**
 * RoleResource.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

namespace App\Components\Scaffold\Services\Role\Responses;

/**
 * Class RoleResource
 * @package App\Components\Scaffold\Services\Role\Responses
 */
class RoleResource
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
                    'name'        => $data->name,
                    'displayName' => $data->display_name,
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