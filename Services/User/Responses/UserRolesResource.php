<?php
/**
 * UserRolesResource.php
 * Created by @anonymoussc on 06/28/2019 2:31 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/2/19 7:09 AM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class UserRolesResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class UserRolesResource
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
     * UserResource constructor.
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
        $user = [];

        if (!empty($data)) {

            $value = $data;

            $user = [
                'type'       => config('scaffold.api.users.type'),
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
                            'self'    => url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/relationships/primary-role"),
                            'related' => url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/primary-role"),
                        ],
                        'data'  => [
                            'type' => config('scaffold.api.roles.type'),
                            'id'   => $value->role['uuid'],
                        ],
                    ];
                } else {
                    $user['relationships']['primary-role'] = null;
                }

                if ($value->roles->isNotEmpty()) {
                    $additionalRoles = $value->roles->map(function($v, $k) {
                        return ['type' => config('scaffold.api.roles.type'), 'id' => $v->uuid];
                    });

                    $user['relationships']['additional-roles'] = [
                        'links' => [
                            'self'    => url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/relationships/additional-roles"),
                            'related' => url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/additional-roles"),
                        ],
                        'data'  => $additionalRoles,
                    ];
                } else {
                    $user['relationships']['additional-roles'] = [];
                }
            }

            $user['links']['self'] = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid");
            // $user['links']['related'] = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/" . config('scaffold.api.roles.type'));

            $records['data'] = $user;
            if (config('scaffold.api.users.hasRelationship') && config('scaffold.api.users.hasIncluded')) {
                $records['included'] = $this->loadCompoundDoc($data);
            }
        } else {
            $records['data'] = [];
        }

        if (config('scaffold.api.users.hasLink')) {
            $records['links'] = [
                'self' => $this->request->fullUrl(),
            ];
        }

        if (config('scaffold.api.users.hasMeta')) {
            $records['meta'] = [
                'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                'author'    => config('scaffold.api.users.authors'),
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

        $usr = $data;

        $newCollection = $usr->roles;
        $newCollection->prepend($usr->role);

        $rolesMerge = $rolesMerge->merge($newCollection);

        $include = $rolesMerge->map(static function($value, $key) {
            $newRole = [
                'type'       => config('scaffold.api.roles.type'),
                'id'         => $value->uuid,
                'attributes' => [
                    'name'        => $value->name,
                    'displayName' => $value->display_name,
                ],
                'links'      => [
                    'self' => url("/api/v1/" . config('scaffold.api.roles.type') . "/$value->uuid"),
                ],
            ];

            return $newRole;
        });

        return $include;
    }
}