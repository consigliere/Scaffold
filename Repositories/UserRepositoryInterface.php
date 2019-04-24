<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/23/19 3:56 PM
 */

/**
 * UserRepositoryInterface.php
 * Created by @anonymoussc on 04/08/2019 11:53 PM.
 */

namespace App\Components\Scaffold\Repositories;


interface UserRepositoryInterface
{
    public function create(array $data = [], array $option = [], array $param = []);

}