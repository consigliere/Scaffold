<?php
/**
 * UserResource.php
 * Created by @anonymoussc on 05/09/2019 7:25 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/20/19 2:03 AM
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
     * @var \Illuminate\Config\Repository|mixed
     */
    private $appname;

    /**
     * @var false|string
     */
    private $year;

    /**
     * UserResource constructor.
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
                'copyright' => "copyrightⒸ $this->year  $this->appname",
                'author'    => config('scaffold.api.users.authors'),
            ];
        }

        return $user;
    }
}