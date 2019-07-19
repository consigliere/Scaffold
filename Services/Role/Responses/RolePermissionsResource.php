<?php
/**
 * RolePermissionsResource.php
 * Created by @anonymoussc on 07/03/2019 1:40 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 1:44 AM
 */

namespace App\Components\Scaffold\Services\Role\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RolePermissionsResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class RolePermissionsResource
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
     * @var \Illuminate\Config\Repository|mixed
     */
    private $appname;

    /**
     * @var false|string
     */
    private $year;

    /**
     * RolePermissionsResource constructor.
     */
    public function __construct()
    {
        $this->auth    = App::get('auth');
        $this->request = App::get('request');
        $this->year    = date('Y');
        $this->appname = config('app.name') ?? config('scaffold.name');
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
        if (!empty($data)) {
            $value = $data;

            $role = [
                'type'       => config('scaffold.api.roles.type'),
                'id'         => $value->uuid,
                'attributes' => [
                    'name'        => $value->name,
                    'displayName' => $value->display_name,
                ],
            ];

            if (config('scaffold.api.roles.hasRelationship')) {
                if ($value->permissions->isNotEmpty()) {
                    $permissions = $value->permissions->map(static function($v, $k) {
                        return ['type' => config('scaffold.api.permissions.type'), 'id' => $v->uuid];
                    });

                    $role['relationships']['permissions'] = [
                        'links' => [
                            'self'    => url("/api/v1/roles/$value->uuid/relationships/permissions"),
                            'related' => url("/api/v1/roles/$value->uuid/permissions"),
                        ],
                        'data'  => $permissions,
                    ];
                } else {
                    $role['relationships']['permissions'] = [];
                }
            }

            $records['data'] = $role;
            if (config('scaffold.api.roles.hasRelationship') && config('scaffold.api.roles.hasIncluded')) {
                $records['included'] = $this->loadCompoundDoc($data);
            }
        } else {
            $records['data'] = [];
        }

        if (config('scaffold.api.roles.hasLink')) {
            $records['links'] = [
                'self' => $this->request->fullUrl(),
            ];
        }

        if (config('scaffold.api.roles.hasMeta')) {
            $records['meta'] = [
                'copyright' => "copyrightⒸ $this->year  $this->appname",
                'author'    => config('scaffold.api.roles.authors'),
            ];
        }

        return $records;
    }

    /**
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return \Illuminate\Support\Collection|static
     */
    private function loadCompoundDoc($data, array $option = [], array $param = [])
    {
        $rolesMerge = new \Illuminate\Database\Eloquent\Collection;

        $rolesMerge = $rolesMerge->merge($data->permissions);

        $include = $rolesMerge->map(static function($value, $key) {
            $newPermission = [
                'type'       => config('scaffold.api.permissions.type'),
                'id'         => $value->uuid,
                'attributes' => [
                    'key'    => $value->key,
                    'entity' => $value->table_name,
                ],
                'links'      => [
                    'self' => url("/api/v1/permissions/$value->uuid"),
                ],
            ];

            return $newPermission;
        });

        return $include;
    }
}