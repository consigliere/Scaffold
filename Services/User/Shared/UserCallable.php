<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/30/19 3:58 PM
 */

/**
 * UserCallable.php
 * Created by @anonymoussc on 04/24/2019 4:55 PM.
 */

namespace App\Components\Scaffold\Services\User\Shared;

trait UserCallable
{
    public function browseResponse(Callable $response, $dataObj, array $option = [], array $param = [])
    {
        return $response($dataObj, $option, $param);
    }

    public function createData(Callable $create, array $data = [], array $option = [], $param = []): array
    {
        return $create($data, $option, $param);
    }

    public function createResponse(Callable $response, $dataObj, array $option = [], array $param = []): array
    {
        return $response($dataObj, $option, $param);
    }

    public function updateData(Callable $update, $uuid, array $data = [], array $option = [], array $param = [])
    {
        return $update($uuid, $data, $option, $param);
    }

    public function updateResponse(Callable $response, $uuid, $dataObj, array $option = [], array $param = [])
    {
        return $response($uuid, $dataObj, $option, $param);
    }
}