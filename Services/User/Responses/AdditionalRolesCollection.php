<?php
/**
 * AdditionalRolesCollection.php
 * Created by @anonymoussc on 06/28/2019 3:20 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 2:03 AM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class AdditionalRolesCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class AdditionalRolesCollection
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
     * @param       $additional
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($additional, array $option = [], array $param = [])
    {
        $records = [];

        if ($additional->isNotEmpty()) {
            $newData = $additional->map(static function($value, $key) use ($param) {
                return [
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
            });

            $records['data'] = $newData;
        } else {
            $records['data'] = [];
        }

        $records['links'] = $this->getLink();
        $records['meta']  = $this->getMeta();

        return $records;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getLink($data = null, array $param = []): array
    {
        $link['self'] = $this->request->fullUrl();

        return $link;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getMeta($data = null, array $param = []): array
    {
        $meta = [];

        $meta['copyright'] = "copyrightⒸ $this->year  $this->appname";
        $meta['author']    = config('scaffold.api.roles.authors');

        return $meta;
    }
}