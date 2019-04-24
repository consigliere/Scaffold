<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/24/19 5:04 PM
 */

/**
 * UserRepository.php
 * Created by @anonymoussc on 04/09/2019 8:32 AM.
 */

namespace App\Components\Scaffold\Repositories\User;

use App\Components\Scaffold\Repositories\Repository;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Config;

class UserRepository extends Repository implements UserRepositoryInterface
{
    private $userCfg;

    /**
     * @return mixed
     */
    protected function getModel()
    {
        $this->userCfg = Config::get('auth.providers.apis.model');

        return new $this->userCfg;
    }

    public function create(array $data = [], array $option = [], array $param = [])
    {
        $user = $this->getModel();

        $user->role_id        = $data['role_id'];
        $user->uuid           = $data['uuid'];
        $user->username       = $data['username'];
        $user->name           = $data['name'];
        $user->email          = $data['email'];
        $user->password       = $data['password'];
        $user->remember_token = $data['remember_token'];
        $user->settings       = $data['settings'];
        if (($data['avatar'] !== '') && ($data['avatar'] !== null) && ($data['avatar'] !== false)) {
            $user->avatar = $data['avatar'];
        }
        // $user->email_verified_at = $data['email_verified_at'];

        $user->save();

        return $user;
    }
}