<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/27/19 4:12 AM
 */

/**
 * UserRepositoryInterface.php
 * Created by @anonymoussc on 04/08/2019 11:53 PM.
 */

namespace App\Components\Scaffold\Repositories;


interface UserRepositoryInterface
{
    public function create(array $data = [], array $option = [], array $param = []);

    public function update($uuid, array $data = [], array $option = [], array $param = []);

}