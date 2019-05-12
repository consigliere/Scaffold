<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/9/19 4:49 AM
 */

/**
 * RoleRepositoryInterface.php
 * Created by @anonymoussc on 04/08/2019 11:54 PM.
 */

namespace App\Components\Scaffold\Repositories;


interface RoleRepositoryInterface
{
    public function browse(array $data = [], array $option = [], array $param = []);

    public function create(array $data = [], array $option = [], array $param = []);

    public function update($id, array $data = [], array $option = [], array $param = []);
}