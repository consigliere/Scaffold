<?php
/**
 * PrimaryRoleResource.php
 * Created by @anonymoussc on 06/27/2019 4:34 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 6:00 AM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class PrimaryRoleResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class PrimaryRoleResource
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
        $this->appName = config('app.name') ?? config('scaffold.name');
    }

    /**
     * @param       $primary
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($primary, array $option = [], array $param = [])
    {
        $records = [];

        if (!empty($primary)) {
            $records['data']['primary-role'] = [
                'type'       => config('scaffold.api.roles.type'),
                'id'         => $primary->uuid,
                'attributes' => [
                    'name'        => $primary->name,
                    'displayName' => $primary->display_name,
                ],
                'links'      => [
                    'self' => url("/api/v1/" . config('scaffold.api.roles.type') . "/$primary->uuid"),
                ],
            ];
        } else {
            $records['data']['primary-role'] = [];
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

        $meta['copyright'] = 'copyrightⒸ ' . date('Y') . ' ' . $this->appName;
        $meta['author']    = config('scaffold.api.roles.authors');

        return $meta;
    }
}