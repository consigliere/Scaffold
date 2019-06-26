<?php
/**
 * RelatedRolesCollection.php
 * Created by @anonymoussc on 06/27/2019 1:10 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/27/19 3:41 AM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RelatedRolesCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class RelatedRolesCollection
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
     * @param       $additional
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($primary, $additional, array $option = [], array $param = [])
    {
        $records = [];

        if (!empty($primary)) {
            $records['data']['primary-role'] = [
                'type' => config('scaffold.api.roles.type'),
                'id'   => $primary->uuid,
            ];
        } else {
            $records['data']['primary-role'] = null;
        }

        if ($additional->isNotEmpty()) {
            $newData = $additional->map(function($value, $key) use ($param) {
                return [
                    'type' => config('scaffold.api.roles.type'),
                    'id'   => $value->uuid,
                ];
            });

            $records['data']['additional-roles'] = $newData;
        } else {
            $records['data']['additional-roles'] = [];
        }

        $records['link'] = $this->getLink();
        $records['meta'] = $this->getMeta();

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