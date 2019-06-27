<?php
/**
 * PermissionResource.php
 * Created by @anonymoussc on 05/12/2019 9:27 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 3:45 AM
 */

namespace App\Components\Scaffold\Services\Permission\Responses;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class PermissionResource
 * @package App\Components\Scaffold\Services\Permission\Responses
 */
class PermissionResource
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
        $this->appName = Config::get('app.name') ?? Config::get('scaffold.name');
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
                'type'       => Config::get('scaffold.api.permissions.type'),
                'id'         => $data->uuid,
                'attributes' => [
                    'key'    => $data->key,
                    'entity' => $data->table_name,
                ],
            ];

            if (Config::get('scaffold.api.permissions.hasLink')) {
                $permission['links'] = [
                    'self' => $this->request->fullUrl(),
                ];
            }

            if (Config::get('scaffold.api.permissions.hasMeta')) {
                $permission['meta'] = [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                    'author'    => Config::get('scaffold.api.permissions.authors'),
                ];
            }
        }

        return $permission;
    }
}