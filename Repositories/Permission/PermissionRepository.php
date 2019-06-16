<?php
/**
 * PermissionRepository.php
 * Created by @anonymoussc on 04/09/2019 8:33 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/16/19 7:02 PM
 */

namespace App\Components\Scaffold\Repositories\Permission;

use App\Components\Scaffold\Entities\Permission;
use App\Components\Scaffold\Repositories\PermissionRepositoryInterface;
use App\Components\Scaffold\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

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
        $paging      = (int)data_get($data, 'header.paging');
        $permissions = $this->getModel();

        return $permissions->paginate($paging);
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
        $permission = $this->getModel();

        $permission->key        = $data['key'];
        $permission->table_name = $data['table_name'];
        $permission->created_by = Auth::id() ?? 0;
        $permission->updated_by = Auth::id() ?? 0;

        $permission->save();

        return $permission;
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
        $permission = $this->getModel()::where('id', '=', $id)->firstOrFail();

        if (isset($data['key']) && !empty($data['key']) && ($data['key'] !== null)) {
            $permission->key = $data['key'];
        }

        if (isset($data['table_name']) && !empty($data['table_name']) && ($data['table_name'] !== null)) {
            $permission->table_name = $data['table_name'];
        }

        $permission->updated_by = Auth::id() ?? 0;

        $permission->save();

        return $permission;
    }
}