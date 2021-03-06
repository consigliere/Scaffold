<?php
/**
 * PermissionCollection.php
 * Created by @anonymoussc on 05/12/2019 9:26 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 1:51 AM
 */

namespace App\Components\Scaffold\Services\Permission\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class PermissionCollection
 * @package App\Components\Scaffold\Services\Permission\Responses
 */
final class PermissionCollection
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
     * PermissionCollection constructor.
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
        $newData = [];
        $records = [];

        if ($data->isNotEmpty()) {
            $newData = $data->map(function($value, $key) use ($param) {
                return [
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

        $meta['copyright'] = "copyrightⒸ $this->year  $this->appname";
        $meta['author']    = config('scaffold.api.permissions.authors');

        return $meta;
    }
}