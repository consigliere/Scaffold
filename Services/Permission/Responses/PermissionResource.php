<?php
/**
 * PermissionResource.php
 * Created by @anonymoussc on 05/12/2019 9:27 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 12:59 AM
 */

namespace App\Components\Scaffold\Services\Permission\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class PermissionResource
 * @package App\Components\Scaffold\Services\Permission\Responses
 */
final class PermissionResource
{
    /**
     * @var \Illuminate\Auth\AuthManager|mixed
     */
    private $auth;

    /**
     * @var mixed
     */
    private $request;

    /**
     * @var mixed
     */
    private $appName;

    /**
     * PermissionResource constructor.
     */
    public function __construct()
    {
        $this->auth    = App::get('auth');
        $this->request = App::get('request');
        $this->appName = config('app.name') ?? config('scaffold.name');
    }

    /**
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($data, array $option = [], array $param = [])
    {
        $permission = [];

        if (!empty($data)) {
            $permission['data'] = [
                'type'       => config('scaffold.api.permissions.type'),
                'id'         => $data->uuid,
                'attributes' => [
                    'key'    => $data->key,
                    'entity' => $data->table_name,
                ],
                'links'      => [
                    'self' => url("/api/v1/permissions/$data->uuid"),
                ],
            ];

            if (config('scaffold.api.permissions.hasLink')) {
                $permission['links'] = [
                    'self' => $this->request->fullUrl(),
                ];
            }

            if (config('scaffold.api.permissions.hasMeta')) {
                $permission['meta'] = [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                    'author'    => config('scaffold.api.permissions.authors'),
                ];
            }
        }

        return $permission;
    }
}