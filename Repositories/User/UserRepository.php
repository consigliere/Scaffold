<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 10:37 AM
 */

/**
 * UserRepository.php
 * Created by @anonymoussc on 04/09/2019 8:32 AM.
 */

namespace App\Components\Scaffold\Repositories\User;

use App\Components\Scaffold\Repositories\Repository;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{

    /**
     * @return mixed
     */
    protected function getModel()
    {
        // TODO: Implement getModel() method.
    }
}