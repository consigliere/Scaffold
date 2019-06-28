<?php
/**
 * UserResource.php
 * Created by @anonymoussc on 05/09/2019 7:25 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 3:37 PM
 */

namespace App\Components\Scaffold\Services\User\Responses;

use Illuminate\Support\Facades\App;

/**
 * Class UserResource
 * @package App\Components\Scaffold\Services\User\Responses
 */
final class UserResource
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
     * UserResource constructor.
     */
    public function __construct()
    {
        $this->auth    = App::get('auth');
        $this->request = App::get('request');
        $this->appName = config('app.name') ?? config('scaffold.name');
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
                'type'       => config('scaffold.api.users.type'),
                'id'         => $data->uuid,
                'attributes' => [
                    'username' => $data->username,
                    'name'     => $data->name,
                    'email'    => $data->email,
                    'avatar'   => $data->avatar,
                    'settings' => $data->settings,
                ],
            ];
        } else {
            $user['data'] = null;
        }

        if (config('scaffold.api.users.hasLink')) {
            $user['links'] = [
                'self' => $this->request->fullUrl(),
            ];
        }

        if (config('scaffold.api.users.hasMeta')) {
            $user['meta'] = [
                'copyright' => 'copyrightⒸ ' . date('Y') . ' ' . $this->appName,
                'author'    => config('scaffold.api.users.authors'),
            ];
        }

        return $user;
    }
}