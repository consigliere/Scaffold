<?php
/**
 * RoleCollection.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 3:47 AM
 */

namespace App\Components\Scaffold\Services\Role\Responses;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class RoleCollection
 * @package App\Components\Scaffold\Services\Role\Responses
 */
class RoleCollection
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
     * RoleCollection constructor.
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
                return [
                    'type'       => Config::get('scaffold.api.roles.type'),
                    'id'         => $value->uuid,
                    'attributes' => [
                        'name'        => $value->name,
                        'displayName' => $value->display_name,
                    ],
                ];
            });

            $records['data']  = $newData;
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

        $meta['copyright'] = 'copyrightⒸ ' . date('Y') . ' ' . $this->appName;
        $meta['author']    = Config::get('scaffold.api.roles.authors');

        return $meta;
    }
}