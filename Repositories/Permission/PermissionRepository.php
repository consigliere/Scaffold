<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/13/19 7:28 AM
 */

/**
 * PermissionRepository.php
 * Created by @anonymoussc on 04/09/2019 8:33 AM.
 */

namespace App\Components\Scaffold\Repositories\Permission;

use App\Components\Scaffold\Entities\Permission;
use App\Components\Scaffold\Repositories\PermissionRepositoryInterface;
use App\Components\Scaffold\Repositories\Repository;

/**
 * Class PermissionRepository
 * @package App\Components\Scaffold\Repositories\Permission
 */
class PermissionRepository extends Repository implements PermissionRepositoryInterface
{

    /**
     * @return mixed
     */
    protected function getModel()
    {
        return new Permission;
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
     * @return mixed
     */
    public function create(array $data = [], array $option = [], array $param = [])
    {
        $role = $this->getModel();

        $role->key        = $data['key'];
        $role->table_name = $data['table_name'];
        $role->created_by = $param['auth.user.id'] ?? 0;
        $role->updated_by = $param['auth.user.id'] ?? 0;

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

        if (isset($data['key']) && !empty($data['key']) && ($data['key'] !== null)) {
            $role->key = $data['key'];
        }

        if (isset($data['table_name']) && !empty($data['table_name']) && ($data['table_name'] !== null)) {
            $role->table_name = $data['table_name'];
        }

        $role->updated_by = $param['auth.user.id'] ?? 0;

        $role->save();

        return $role;
    }
}