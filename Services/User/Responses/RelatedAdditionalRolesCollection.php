<?php
/**
 * RelatedAdditionalRolesCollection.php
 * Created by @anonymoussc on 06/27/2019 4:54 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/2/19 4:48 PM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class RelatedAdditionalRolesCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class RelatedAdditionalRolesCollection
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
                    'type' => config('scaffold.api.roles.type'),
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
            'related' => $related,
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