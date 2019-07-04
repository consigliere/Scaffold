<?php
/**
 * RelatedPermissionCollection.php
 * Created by @anonymoussc on 07/02/2019 8:05 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/5/19 2:53 AM
 */

namespace App\Components\Scaffold\Services\Role\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RelatedPermissionCollection
 * @package App\Components\Scaffold\Services\Role\Responses
 */
final class RelatedPermissionCollection
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
     * RelatedPermissionCollection constructor.
     */
    public function __construct()
    {
        $this->auth    = App::get('auth');
        $this->request = App::get('request');
        $this->appName = config('app.name') ?? config('scaffold.name');
    }

    /**
     * @param       $permissions
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke($permissions, array $option = [], array $param = [])
    {
        if ($permissions->isNotEmpty()) {
            $newData = $permissions->map(static function($value, $key) use ($param) {
                return [
                    'type' => config('scaffold.api.permissions.type'),
                    'id'   => $value->uuid,
                ];
            });

            $records['data'] = $newData;
        } else {
            $records['data'] = [];
        }

        $records['links'] = $this->getLinks();
        $records['meta']  = $this->getMeta();

        return $records;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getLinks($data = null, array $param = []): array
    {
        $self    = $this->request->fullUrl();
        $related = str_replace('/relationships', '', $self);

        $links = [
            'self'    => $self,
            /*'related' => $related,*/
        ];

        return $links;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getMeta($data = null, array $param = []): array
    {
        $year = date('Y');
        $name = $this->appName;

        $meta = [
            'copyright' => "copyrightⒸ $year $name",
            'author'    => config('scaffold.api.roles.authors'),
        ];

        return $meta;
    }
}