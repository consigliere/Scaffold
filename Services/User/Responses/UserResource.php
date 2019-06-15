<?php
/**
 * UserResource.php
 * Created by @anonymoussc on 05/09/2019 7:25 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/13/19 5:56 PM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class UserResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
class UserResource
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
     * @return array
     */
    public function __invoke($data, array $option = [], array $param = [])
    {
        $user = [];

        if (!empty($data)) {
            $user['data'] = [
                'type'       => Config::get('scaffold.api.users.type'),
                'id'         => $data->uuid,
                'attributes' => [
                    'username' => $data->username,
                    'name'     => $data->name,
                    'email'    => $data->email,
                    'avatar'   => $data->avatar,
                    'settings' => $data->settings,
                ],
            ];

            if (Config::get('scaffold.api.users.hasLink')) {
                $user['link'] = [
                    'self' => $this->request->fullUrl(),
                ];
            }

            if (Config::get('scaffold.api.users.hasMeta')) {
                $user['meta'] = [
                    'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                    'author'    => Config::get('scaffold.api.users.authors'),
                ];
            }
        }

        return $user;
    }
}