<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/2/19 1:53 AM
 */

/**
 * UserRepository.php
 * Created by @anonymoussc on 04/09/2019 8:32 AM.
 */

namespace App\Components\Scaffold\Repositories\User;

use App\Components\Scaffold\Repositories\Repository;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Config;

/**
 * Class UserRepository
 * @package App\Components\Scaffold\Repositories\User
 */
class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * @var
     */
    private $userCfg;

    /**
     * @return mixed
     */
    protected function getModel()
    {
        $this->userCfg = Config::get('auth.providers.apis.model');

        return new $this->userCfg;
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function browse(array $data = [], array $option = [], array $param = [])
    {
        $paging = (int)$data['header']['paging'];
        $user   = $this->getModel();

        return $user->paginate($paging);
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
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
        if (isset($data['avatar']) && !empty($data['avatar']) && ($data['avatar'] !== null)) {
            $user->avatar = $data['avatar'];
        }

        if (isset($data['email_verified_at']) && !empty($data['email_verified_at']) && ($data['email_verified_at'] !== null)) {
            $user->email_verified_at = $data['email_verified_at'];
        }

        $user->save();

        return $user;
    }

    /**
     * @param       $id
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function update($id, array $data = [], array $option = [], array $param = [])
    {
        $user = $this->getModel()::where('id', '=', $id)->firstOrFail();

        if (isset($data['role_id']) && !empty($data['role_id']) && ($data['role_id'] !== null)) {
            $user->role_id = $data['role_id'];
        }
        if (isset($data['username']) && !empty($data['username']) && ($data['username'] !== null)) {
            $user->username = $data['username'];
        }
        if (isset($data['name']) && !empty($data['name']) && ($data['name'] !== null)) {
            $user->name = $data['name'];
        }
        if (isset($data['email']) && !empty($data['email']) && ($data['email'] !== null)) {
            $user->email = $data['email'];
        }
        if (isset($data['password']) && !empty($data['password']) && ($data['password'] !== null)) {
            $user->password = $data['password'];
        }
        if (isset($data['remember_token']) && !empty($data['remember_token']) && ($data['remember_token'] !== null)) {
            $user->remember_token = $data['remember_token'];
        }
        if (isset($data['settings']) && !empty($data['settings']) && ($data['settings'] !== null)) {
            $user->settings = $data['settings'];
        }
        if (isset($data['avatar']) && !empty($data['avatar']) && ($data['avatar'] !== null)) {
            $user->avatar = $data['avatar'];
        }
        if (isset($data['email_verified_at']) && !empty($data['email_verified_at']) && ($data['email_verified_at'] !== null)) {
            $user->email_verified_at = $data['email_verified_at'];
        }

        $user->save();

        return $user;
    }

    /**
     * @param       $uuid
     * @param array $param
     *
     * @return mixed
     */
    public function getIdBy($uuid, array $param = [])
    {
        $users = $this->getModel()::where('uuid', '=', $uuid)->orWhere('id', '=', $uuid)->get();

        $uid = [];
        $i   = 0;
        foreach ($users as $user) {
            $uid[$i] = $this->getModel()::where([
                ['id', $user->id],
                ['uuid', $uuid],
            ])->first();

            if (null === $uid[$i]) {
                unset($uid[$i]);
            } else {
                return $uid[$i]->id;
            }

            $i++;
        }
    }
}