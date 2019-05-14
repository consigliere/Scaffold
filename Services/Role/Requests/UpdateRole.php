<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/14/19 11:36 AM
 */

/**
 * UpdateRole.php
 * Created by @anonymoussc on 05/10/2019 5:00 AM.
 */

namespace App\Components\Scaffold\Services\Role\Requests;

/**
 * Class UpdateRole
 * @package App\Components\Scaffold\Services\Role\Requests
 */
class UpdateRole
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