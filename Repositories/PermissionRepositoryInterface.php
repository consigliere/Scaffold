<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/13/19 7:29 AM
 */

/**
 * PermissionRepositoryInterface.php
 * Created by @anonymoussc on 04/08/2019 11:54 PM.
 */

namespace App\Components\Scaffold\Repositories;

/**
 * Interface PermissionRepositoryInterface
 * @package App\Components\Scaffold\Repositories
 */
interface PermissionRepositoryInterface
{
    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function browse(array $data = [], array $option = [], array $param = []);

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function create(array $data = [], array $option = [], array $param = []);

    /**
     * @param       $id
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function update($id, array $data = [], array $option = [], array $param = []);
}