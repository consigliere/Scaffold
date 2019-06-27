<?php
/**
 * UserCollection.php
 * Created by @anonymoussc on 05/09/2019 6:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/27/19 3:50 PM
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

    /**
     * UserCollection constructor.
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
     * @return mixed
     */
    public function __invoke($data, array $option = [], array $param = [])
    {
        $records = [];

        if ($data->isNotEmpty()) {
            $newData = $data->map(function($value, $key) use ($param) {
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
                            [
                                'type' => config('scaffold.api.roles.type'),
                                'id'   => $value->role['uuid'],
                            ]
                        ];
                    } else {
                        $user['relationships']['primary-role'] = [];
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

                return $user;
            });

            $records['data'] = $newData;
            if (config('scaffold.api.users.hasRelationship') && config('scaffold.api.users.hasIncluded')) {
                $records['included'] = $this->loadCompoundDoc($data);
            }
            $records['links'] = $this->getLink($data);
            $records['meta']  = $this->getMeta($data);
        } else {
            $records['data']  = [];
            $records['links'] = $this->getLink($data);
            $records['meta']  = $this->getMeta($data);
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

        foreach ($data as $usr) {
            $newCollection = $usr->roles;
            $newCollection->prepend($usr->role);

            $rolesMerge = $rolesMerge->merge($newCollection);
        }

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

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getLink($data, array $param = []): array
    {
        $link = [];

        if ($data->isNotEmpty()) {
            $link = [
                'first' => $data->url(1),
                'last'  => $data->url($data->lastPage()),
                'prev'  => $data->previousPageUrl(),
                'next'  => $data->nextPageUrl(),
            ];
        } else {
            $link['self'] = $this->request->fullUrl();
        }

        return $link;
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
                "current_page" => $data->currentPage(),
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