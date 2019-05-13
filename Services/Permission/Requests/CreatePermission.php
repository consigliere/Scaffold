<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/13/19 7:31 AM
 */

/**
 * CreatePermission.php
 * Created by @anonymoussc on 05/12/2019 9:26 AM.
 */

namespace App\Components\Scaffold\Services\Permission\Requests;

/**
 * Class CreatePermission
 * @package App\Components\Scaffold\Services\Permission\Requests
 */
class CreatePermission
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
        $dataIn     = $data['form'];
        $permission = [];

        if (!empty($dataIn)) {
            $permission = [
                'key'        => $dataIn['key'],
                'table_name' => $dataIn['entity'],
            ];
        }

        return $permission;
    }
}