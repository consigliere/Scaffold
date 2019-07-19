<?php
/**
 * RoleResource.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 1:08 AM
 */

namespace App\Components\Scaffold\Services\Role\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RoleResource
 * @package App\Components\Scaffold\Services\Role\Responses
 */
final class RoleResource
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
        $role = [];

        if (!empty($data)) {
            $role['data'] = [
                'type'       => config('scaffold.api.roles.type'),
                'id'         => $data->uuid,
                'attributes' => [
                    'name'        => $data->name,
                    'displayName' => $data->display_name,
                ],
            ];

            if (config('scaffold.api.roles.hasLink')) {
                $role['links'] = [
                    'self' => $this->request->fullUrl(),
                ];
            }

            if (config('scaffold.api.roles.hasMeta')) {
                $role['meta'] = [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                    'author'    => config('scaffold.api.roles.authors'),
                ];
            }
        }

        return $role;
    }
}