<?php
/**
 * UserRepository.php
 * Created by @anonymoussc on 04/09/2019 8:32 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/3/19 1:35 AM
 */

namespace App\Components\Scaffold\Repositories\User;

use App\Components\Scaffold\Repositories\Repository;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
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
        $paging = (int)data_get($data, 'header.paging');

        if (config('scaffold.api.users.hasRelationship') || config('scaffold.api.users.hasIncluded')) {
            $users = $this->getModel()::with('role')->with('roles');
        } else {
            $users = $this->getModel();
        }

        return $users->paginate($paging);
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
        $user->created_by     = Auth::id() ?? 0;
        $user->updated_by     = Auth::id() ?? 0;

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
        $user->updated_by = Auth::id() ?? 0;

        $user->save();

        return $user;
    }

    /**
     * @param       $id
     * @param array $options
     * @param array $param
     *
     * @return mixed
     */
    public function firstById($id, array $options = [], array $param = [])
    {
        if (config('scaffold.api.users.hasRelationship') || config('scaffold.api.users.hasIncluded')) {
            $user = $this->getModel()::where('id', $id)->with('role')->with('roles');
        } else {
            $user = $this->getModel()::where('id', $id);
        }

        return $user->first();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function primaryRoles($id)
    {
        $user = $this->getModel()::find($id);

        return $user->role;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function additionalRoles($id)
    {
        $user = $this->getModel()::find($id);

        return $user->roles;
    }

    /**
     * @param       $id
     * @param null  $roleId
     * @param array $option
     * @param array $param
     */
    public function attachUserRoles($id, $roleId = null, array $option = [], array $param = [])
    {
        $user = $this->getModel()::find($id);

        $user->roles()->attach($roleId);
    }

    /**
     * @param       $id
     * @param null  $roleId
     * @param array $option
     * @param array $param
     */
    public function detachUserRoles($id, $roleId = null, array $option = [], array $param = [])
    {
        $user = $this->getModel()::find($id);

        if (null === $roleId) {
            $user->roles()->detach();
        } else {
            $user->roles()->detach($roleId);
        }
    }
}