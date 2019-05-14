<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/14/19 11:36 AM
 */

/**
 * UpdatePermission.php
 * Created by @anonymoussc on 05/12/2019 9:26 AM.
 */

namespace App\Components\Scaffold\Services\Permission\Requests;

/**
 * Class UpdatePermission
 * @package App\Components\Scaffold\Services\Permission\Requests
 */
class UpdatePermission
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
                'key'        => strtolower($dataIn['key']),
                'table_name' => $dataIn['entity'],
            ];
        }

        return $permission;
    }
}