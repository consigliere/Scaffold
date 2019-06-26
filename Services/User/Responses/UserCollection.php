<?php
/**
 * UserCollection.php
 * Created by @anonymoussc on 05/09/2019 6:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/26/19 7:30 PM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class UserCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
class UserCollection
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
        $this->appName = Config::get('app.name') ?? Config::get('scaffold.name');
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
        $newData = [];
        $records = [];

        if ($data->isNotEmpty()) {
            $newData = $data->map(function($value, $key) use ($param) {
                $nd['type']                   = Config::get('scaffold.api.users.type');
                $nd['id']                     = $value->uuid;
                $nd['attributes']['username'] = $value->username;
                $nd['attributes']['name']     = $value->name;
                $nd['attributes']['email']    = $value->email;
                $nd['attributes']['avatar']   = $value->avatar;
                $nd['attributes']['settings'] = $value->settings;

                if (Config::get('scaffold.api.users.hasRelationship')) {
                    if (null !== $value->role) {
                        $nd['relationship']['primary-role']['links']['self']    = url("/api/v1/users/$value->uuid/relationship/primary-role");
                        $nd['relationship']['primary-role']['links']['related'] = url("/api/v1/users/$value->uuid/primary-role");
                        $nd['relationship']['primary-role']['data']             = [
                            'type' => Config::get('scaffold.api.roles.type'),
                            'id'   => $value->role['uuid'],
                        ];
                    } else {
                        $nd['relationship']['primary-role'] = [];
                    }

                    if ($value->roles->isNotEmpty()) {
                        $additionalRoles = $value->roles->map(function($v, $k) {
                            return ['type' => Config::get('scaffold.api.roles.type'), 'id' => $v->uuid];
                        });

                        $nd['relationship']['additional-roles']['links']['self']    = url("/api/v1/users/$value->uuid/relationship/additional-roles");
                        $nd['relationship']['additional-roles']['links']['related'] = url("/api/v1/users/$value->uuid/additional-roles");
                        $nd['relationship']['additional-roles']['data']             = $additionalRoles;

                    } else {
                        $nd['relationship']['additional-roles'] = [];
                    }
                }

                $nd['links']['self'] = url("/api/v1/" . Config::get('scaffold.api.users.type') . "/$value->uuid");

                return $nd;
            });

            $x = new \Illuminate\Database\Eloquent\Collection;
            foreach ($data as $dt) {
                $newCollection = $dt->roles;
                $newCollection->prepend($dt->role);

                $x = $x->merge($newCollection);
            }

            $newInclude = $x->map(function($value, $key) use ($param) {
                $t['type']                     = 'roles';
                $t['id']                       = $value->uuid;
                $t['attribute']['name']        = $value->name;
                $t['attribute']['displayName'] = $value->display_name;
                $t['links']['self']            = url('/api/v1/roles/' . $value->uuid);

                return $t;
            });

            // dd($newInclude);

            $records['data']     = $newData;
            $records['included'] = $newInclude;
            $records['links']    = $this->getLink($data);
            $records['meta']     = $this->getMeta($data);
        } else {
            $records['data']     = [];
            $records['included'] = [];
            $records['links']    = $this->getLink($data);
            $records['meta']     = $this->getMeta($data);
        }

        return $records;
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
        $meta['author']    = Config::get('scaffold.api.users.authors');

        return $meta;
    }
}