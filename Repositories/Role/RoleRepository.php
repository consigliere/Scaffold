<?php
/**
 * RoleRepository.php
 * Created by @anonymoussc on 04/09/2019 8:32 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/16/19 6:22 PM
 */

namespace App\Components\Scaffold\Repositories\Role;

use App\Components\Scaffold\Entities\Role;
use App\Components\Scaffold\Repositories\Repository;
use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use Illuminate\Support\Facades\Auth;

/**
 * Class RoleRepository
 * @package App\Components\Scaffold\Repositories\Role
 */
class RoleRepository extends Repository implements RoleRepositoryInterface
{
    /**
     * @var $role
     */
    protected $role;

    /**
     * @return \App\Components\Scaffold\Entities\Role
     */
    protected function getModel()
    {
        return new Role;
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
        $roles  = $this->getModel();

        return $roles->paginate($paging);
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return \App\Components\Scaffold\Entities\Role
     */
    public function create(array $data = [], array $option = [], array $param = [])
    {
        $role = $this->getModel();

        $role->name         = $data['name'];
        $role->display_name = $data['display_name'];
        $role->created_by   = Auth::id() ?? 0;
        $role->updated_by   = Auth::id() ?? 0;

        $role->save();

        return $role;
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
        $role = $this->getModel()::where('id', '=', $id)->firstOrFail();

        if (isset($data['name']) && !empty($data['name']) && ($data['name'] !== null)) {
            $role->name = $data['name'];
        }

        if (isset($data['display_name']) && !empty($data['display_name']) && ($data['display_name'] !== null)) {
            $role->display_name = $data['display_name'];
        }

        $role->updated_by = Auth::id() ?? 0;

        $role->save();

        return $role;
    }

    /**
     * @return mixed
     */
    public function getIds()
    {
        return $this->getModel()::where('id', '>', 0)->pluck('id')->toArray();
    }
}