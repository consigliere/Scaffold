<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/14/19 11:36 AM
 */

/**
 * CreateRole.php
 * Created by @anonymoussc on 05/10/2019 4:59 AM.
 */

namespace App\Components\Scaffold\Services\Role\Requests;

/**
 * Class CreateRole
 * @package App\Components\Scaffold\Services\Role\Requests
 */
class CreateRole
{
    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function __invoke(array $data = [], array $option = [], array $param = [])
    {
        $dataIn = $data['form'];
        $role   = [];

        if (!empty($dataIn)) {
            $role = [
                'name'         => strtolower($dataIn['name']),
                'display_name' => $dataIn['displayName'],
            ];
        }

        return $role;
    }
}