<?php
/**
 * UserCollection.php
 * Created by @anonymoussc on 05/09/2019 6:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/2/19 6:31 AM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class UserCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class UserCollection
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

    private $userType;
    private $roleType;

    /**
     * UserCollection constructor.
     */
    public function __construct()
    {
        $this->auth     = App::get('auth');
        $this->request  = App::get('request');
        $this->appName  = config('app.name') ?? config('scaffold.name');
        $this->userType = config('scaffold.api.users.type');
        $this->roleType = config('scaffold.api.roles.type');
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
            $newData = $data->map(function($value, $key) use ($param) {
                $user = [
                    'type'       => $this->userType,
                    'id'         => $value->uuid,
                    'attributes' => [
                        'username' => $value->username,
                        'name'     => $value->name,
                        'email'    => $value->email,
                        'avatar'   => $value->avatar,
                        'settings' => $value->settings,
                    ],
                ];

                if (config('scaffold.api.users.hasRelationship')) {
                    if (null !== $value->role) {
                        $user['relationships']['primary-role'] = [
                            'links' => [
                                'self'    => url("/api/v1/$this->userType/$value->uuid/relationships/primary-role"),
                                'related' => url("/api/v1/$this->userType/$value->uuid/primary-role"),
                            ],
                            'data'  => [
                                'type' => $this->roleType,
                                'id'   => $value->role['uuid'],
                            ],
                        ];
                    } else {
                        $user['relationships']['primary-role'] = null;
                    }

                    if ($value->roles->isNotEmpty()) {
                        $additionalRoles = $value->roles->map(function($v, $k) {
                            return ['type' => $this->roleType, 'id' => $v->uuid];
                        });

                        $user['relationships']['additional-roles'] = [
                            'links' => [
                                'self'    => url("/api/v1/$this->userType/$value->uuid/relationships/additional-roles"),
                                'related' => url("/api/v1/$this->userType/$value->uuid/additional-roles"),
                            ],
                            'data'  => $additionalRoles,
                        ];
                    } else {
                        $user['relationships']['additional-roles'] = [];
                    }
                }

                $user['links'] = [
                    'self'    => url("/api/v1/$this->userType/$value->uuid"),
                    /*'related' => url("/api/v1/$this->userType/$value->uuid/$this->roleType"),*/
                ];

                return $user;
            });

            $records['data'] = $newData;
            if (config('scaffold.api.users.hasRelationship') && config('scaffold.api.users.hasIncluded')) {
                $records['included'] = $this->loadCompoundDoc($data);
            }
        } else {
            $records['data'] = [];
        }

        $records['links'] = $this->getLinks($data);
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
        $rolesMerge = new \Illuminate\Database\Eloquent\Collection;

        foreach ($data as $usr) {
            $newCollection = $usr->roles;
            $newCollection->prepend($usr->role);

            $rolesMerge = $rolesMerge->merge($newCollection);
        }

        $include = $rolesMerge->map(static function($value, $key) {
            $newRole = [
                'type'      => config('scaffold.api.roles.type'),
                'id'        => $value->uuid,
                'attribute' => [
                    'name'        => $value->name,
                    'displayName' => $value->display_name,
                ],
                'links'     => [
                    'self' => url("/api/v1/" . config('scaffold.api.roles.type') . "/$value->uuid"),
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
    private function getLinks($data, array $param = []): array
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

        $meta['copyright'] = 'copyrightⒸ ' . date('Y') . ' ' . $this->appName;
        $meta['author']    = config('scaffold.api.users.authors');

        return $meta;
    }
}