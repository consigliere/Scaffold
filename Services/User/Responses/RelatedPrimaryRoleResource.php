<?php
/**
 * RelatedPrimaryRoleResource.php
 * Created by @anonymoussc on 06/27/2019 3:33 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 2:03 AM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RelatedPrimaryRoleResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class RelatedPrimaryRoleResource
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
            $records['data'] = [
                'type' => config('scaffold.api.roles.type'),
                'id'   => $primary->uuid,
            ];
        } else {
            $records['data'] = null;
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