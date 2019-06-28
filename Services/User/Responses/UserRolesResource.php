<?php
/**
 * UserRolesResource.php
 * Created by @anonymoussc on 06/28/2019 2:31 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 2:35 PM
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

            $user['type']                   = config('scaffold.api.users.type');
            $user['id']                     = $value->uuid;
            $user['attributes']['username'] = $value->username;
            $user['attributes']['name']     = $value->name;
            $user['attributes']['email']    = $value->email;
            $user['attributes']['avatar']   = $value->avatar;
            $user['attributes']['settings'] = $value->settings;

            if (config('scaffold.api.users.hasRelationship')) {
                if (null !== $value->role) {
                    $user['relationships']['primary-role']['links']['self']    = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/relationships/primary-role");
                    $user['relationships']['primary-role']['links']['related'] = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/primary-role");
                    $user['relationships']['primary-role']['data']             = [
                        'type' => config('scaffold.api.roles.type'),
                        'id'   => $value->role['uuid'],
                    ];
                } else {
                    $user['relationships']['primary-role'] = null;
                }

                if ($value->roles->isNotEmpty()) {
                    $additionalRoles = $value->roles->map(function($v, $k) {
                        return ['type' => config('scaffold.api.roles.type'), 'id' => $v->uuid];
                    });

                    $user['relationships']['additional-roles']['links']['self']    = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/relationships/additional-roles");
                    $user['relationships']['additional-roles']['links']['related'] = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/additional-roles");
                    $user['relationships']['additional-roles']['data']             = $additionalRoles;
                } else {
                    $user['relationships']['additional-roles'] = [];
                }
            }

            $user['links']['self']    = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid");
            $user['links']['related'] = url("/api/v1/" . config('scaffold.api.users.type') . "/$value->uuid/" . config('scaffold.api.roles.type'));

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
            $newRole['type']                     = config('scaffold.api.roles.type');
            $newRole['id']                       = $value->uuid;
            $newRole['attribute']['name']        = $value->name;
            $newRole['attribute']['displayName'] = $value->display_name;
            $newRole['links']['self']            = url("/api/v1/" . config('scaffold.api.roles.type') . "/$value->uuid");

            return $newRole;
        });

        return $include;
    }
}