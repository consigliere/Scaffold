<?php
/**
 * UserRepositoryInterface.php
 * Created by @anonymoussc on 04/08/2019 11:53 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/20/19 3:37 AM
 */

namespace App\Components\Scaffold\Repositories;


/**
 * Interface UserRepositoryInterface
 * @package App\Components\Scaffold\Repositories
 */
interface UserRepositoryInterface
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