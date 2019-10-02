<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 10/3/19 2:24 AM
 */

/**
 * UserRolesResource.php
 * Created by @anonymoussc on 06/28/2019 2:31 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

use App\Components\Scaffold\Services\User\Shared\ResponseUserGetLinksResource;
use App\Components\Scaffold\Services\User\Shared\ResponseUserGetMetaResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

/**
 * Class UserRolesResource
 *
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class UserRolesResource
{
    use ResponseUserGetLinksResource, ResponseUserGetMetaResource;

    /**
     * @var \Illuminate\Auth\AuthManager|mixed
     */
    private $auth;

    /**
     * @var mixed
     */
    private $request;

    /**
     * @var string
     */
    private $version;

    /**
     * UserResource constructor.
     */
    public function __construct()
    {
        $this->auth    = App::get('auth');
        $this->request = App::get('request');
        $this->version = $this->request->segment(2);
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
                    $user['relationships']['primary-role'] = $this->relationshipPrimaryRole($value);
                } else {
                    $user['relationships']['primary-role'] = null;
                }

                if ($value->roles->isNotEmpty()) {
                    $user['relationships']['additional-roles'] = $this->relationshipAdditionalRole($value);
                } else {
                    $user['relationships']['additional-roles'] = [];
                }
            }

            $records['data'] = $user;

            if (config('scaffold.api.users.hasRelationship') && config('scaffold.api.users.hasIncluded')) {
                $records['included'] = $this->compoundDocument($data);
            }
        } else {
            $records['data'] = [];
        }

        if (config('scaffold.api.users.hasLink')) {
            $records['links'] = $this->getLinks();
        }

        if (config('scaffold.api.users.hasMeta')) {
            $records['meta'] = $this->getMetas();
        }

        return $records;
    }

    /**
     * @param $value
     *
     * @return array
     */
    private function relationshipPrimaryRole($value): array
    {
        return [
            'links' => [
                'self'    => url("/api/$this->version/" . config('scaffold.api.users.type') . "/$value->uuid/relationships/primary-role"),
                'related' => url("/api/$this->version/" . config('scaffold.api.users.type') . "/$value->uuid/primary-role"),
            ],
            'data'  => [
                'type' => config('scaffold.api.roles.type'),
                'id'   => $value->role['uuid'],
            ],
        ];
    }

    /**
     * @param $value
     *
     * @return array
     */
    private function relationshipAdditionalRole($value): array
    {
        $additionalRoles = $value->roles->map(static function ($v, $k) {
            return ['type' => config('scaffold.api.roles.type'), 'id' => $v->uuid];
        });

        return [
            'links' => [
                'self'    => url("/api/$this->version/" . config('scaffold.api.users.type') . "/$value->uuid/relationships/additional-roles"),
                'related' => url("/api/$this->version/" . config('scaffold.api.users.type') . "/$value->uuid/additional-roles"),
            ],
            'data'  => $additionalRoles,
        ];
    }

    /**
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return \Illuminate\Support\Collection|static
     */
    private function compoundDocument($data, array $option = [], array $param = [])
    {
        $rolesMerge = new Collection;

        $newCollection = $data->roles;
        $newCollection->prepend($data->role);

        $rolesMerge = $rolesMerge->merge($newCollection);
        $include    = $rolesMerge->map(function ($value, $key) {
            $newRole = [
                'type'       => config('scaffold.api.roles.type'),
                'id'         => $value->uuid,
                'attributes' => [
                    'name'        => $value->name,
                    'displayName' => $value->display_name,
                ],
            ];

            if ($value->permissions->isNotEmpty()) {
                $permissions = $value->permissions->map(static function ($v, $k) {
                    return ['type' => config('scaffold.api.permissions.type'), 'id' => $v->uuid];
                });

                $newRole['relationships']['permissions'] = [
                    'data' => $permissions,
                ];
            }

            $newRole['links']['self'] = url("/api/$this->version/" . config('scaffold.api.roles.type') . "/$value->uuid");

            return $newRole;
        });

        return $include;
    }
}
