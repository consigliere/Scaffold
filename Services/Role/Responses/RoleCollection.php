<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 8/1/19 3:17 PM
 */

/**
 * RoleCollection.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

namespace App\Components\Scaffold\Services\Role\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RoleCollection
 * @package App\Components\Scaffold\Services\Role\Responses
 */
final class RoleCollection
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
     * RoleCollection constructor.
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
     * @return mixed
     */
    public function __invoke($data, array $option = [], array $param = [])
    {
        $records = [];

        if ($data->isNotEmpty()) {
            $newData = $data->map(static function($value, $key) use ($param) {
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
                        $relatedPermissions = $value->permissions->map(static function($v, $k) {
                            return ['type' => config('scaffold.api.permissions.type'), 'id' => $v->uuid];
                        });

                        $role['relationship']['permissions'] = [
                            'links' => [
                                'self'    => url("/api/v1/roles/$value->uuid/relationships/permissions"),
                                'related' => url("/api/v1/roles/$value->uuid/permissions"),
                            ],
                            'data'  => $relatedPermissions,
                        ];
                    } else {
                        $role['relationship']['permissions'] = [];
                    }
                }

                $role['links'] = [
                    'self'    => url("/api/v1/roles/$value->uuid"),
                    'related' => url("/api/v1/roles/$value->uuid/permissions"),
                ];

                return $role;
            });

            $records['data'] = $newData;
            if (config('scaffold.api.roles.hasRelationship') && config('scaffold.api.roles.hasIncluded')) {
                $records['included'] = $this->loadCompoundDoc($data);
            }
        } else {
            $records['data'] = [];
        }

        $records['links'] = $this->getLink($data);
        $records['meta']  = $this->getMeta($data);

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
        $newPermission = new \Illuminate\Database\Eloquent\Collection;

        foreach ($data as $roles) {
            $newPermission = $newPermission->merge($roles->permissions);
        }

        $include = $newPermission->map(static function($value, $key) {
            $newRole = [
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

            return $newRole;
        });

        return $include;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getLink($data, array $param = []): array
    {
        $links = [];

        if ($data->isNotEmpty()) {
            $links = [
                'first' => $data->url(1),
                'last'  => $data->url($data->lastPage()),
                'prev'  => $data->previousPageUrl(),
                'next'  => $data->nextPageUrl(),
            ];
        } else {
            $links['self'] = $this->request->fullUrl();
        }

        return $links;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getMeta($data, array $param = []): array
    {
        $meta = [];

        if ($data->isNotEmpty()) {
            $meta = [
                'current_page' => $data->currentPage(),
                'from'         => $data->firstItem(),
                'last_page'    => $data->lastPage(),
                'path'         => $this->request->url(),
                'per_page'     => $data->perPage(),
                'to'           => $data->lastItem(),
                'total'        => $data->total(),
            ];
        }

        $meta['copyright'] = "copyrightⒸ $this->year  $this->appname";
        $meta['author']    = config('scaffold.api.roles.authors');

        return $meta;
    }
}