<?php
/**
 * CreateRole.php
 * Created by @anonymoussc on 05/10/2019 4:59 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 6:15 AM
 */

namespace App\Components\Scaffold\Services\Role\Requests;

/**
 * Class CreateRole
 * @package App\Components\Scaffold\Services\Role\Requests
 */
final class CreateRole
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
                'name'         => strtolower($dataIn['name']),
                'display_name' => $dataIn['displayName'],
            ];
        }

        return $role;
    }
}