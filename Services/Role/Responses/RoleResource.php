<?php
/**
 * RoleResource.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/16/19 6:42 PM
 */

namespace App\Components\Scaffold\Services\Role\Responses;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class RoleResource
 * @package App\Components\Scaffold\Services\Role\Responses
 */
class RoleResource
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
     * RoleResource constructor.
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
        $role = [];

        if (!empty($data)) {
            $role['data'] = [
                'type'       => Config::get('scaffold.api.roles.type'),
                'id'         => $data->uuid,
                'attributes' => [
                    'name'        => $data->name,
                    'displayName' => $data->display_name,
                ],
            ];

            if (Config::get('scaffold.api.roles.hasLink')) {
                $role['link'] = [
                    'self' => $this->request->fullUrl(),
                ];
            }

            if (Config::get('scaffold.api.roles.hasMeta')) {
                $role['meta'] = [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                    'author'    => Config::get('scaffold.api.roles.authors'),
                ];
            }
        }

        return $role;
    }
}