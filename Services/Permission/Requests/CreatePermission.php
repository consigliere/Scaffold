<?php
/**
 * CreatePermission.php
 * Created by @anonymoussc on 05/12/2019 9:26 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/28/19 6:15 AM
 */

namespace App\Components\Scaffold\Services\Permission\Requests;

/**
 * Class CreatePermission
 * @package App\Components\Scaffold\Services\Permission\Requests
 */
final class CreatePermission
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
        $dataIn     = $data['input'];
        $permission = [];

        if (!empty($dataIn)) {
            $permission = [
                'key'        => strtolower($dataIn['key']),
                'table_name' => $dataIn['entity'],
            ];
        }

        return $permission;
    }
}