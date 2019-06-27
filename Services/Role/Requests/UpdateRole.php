<?php
/**
 * UpdateRole.php
 * Created by @anonymoussc on 05/10/2019 5:00 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 6:15 AM
 */

namespace App\Components\Scaffold\Services\Role\Requests;

/**
 * Class UpdateRole
 * @package App\Components\Scaffold\Services\Role\Requests
 */
final class UpdateRole
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
        $dataIn = $data['input'];
        $role   = [];

        if (!empty($dataIn)) {
            $role = [
                'name'         => $dataIn['name'] ? strtolower($dataIn['name']) : null,
                'display_name' => $dataIn['displayName'],
            ];
        }

        return $role;
    }
}