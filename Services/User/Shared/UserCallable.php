<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/2/19 12:31 AM
 */

/**
 * UserCallable.php
 * Created by @anonymoussc on 04/24/2019 4:55 PM.
 */

namespace App\Components\Scaffold\Services\User\Shared;

/**
 * Trait UserCallable
 * @package App\Components\Scaffold\Services\User\Shared
 */
trait UserCallable
{
    /**
     * @param callable $response
     * @param          $dataObj
     * @param array    $option
     * @param array    $param
     *
     * @return mixed
     */
    public function browseResponse(Callable $response, $dataObj, array $option = [], array $param = [])
    {
        return $response($dataObj, $option, $param);
    }

    /**
     * @param callable $create
     * @param array    $data
     * @param array    $option
     * @param array    $param
     *
     * @return array
     */
    public function createData(Callable $create, array $data = [], array $option = [], $param = []): array
    {
        return $create($data, $option, $param);
    }

    /**
     * @param callable $response
     * @param          $dataObj
     * @param array    $option
     * @param array    $param
     *
     * @return array
     */
    public function createResponse(Callable $response, $dataObj, array $option = [], array $param = []): array
    {
        return $response($dataObj, $option, $param);
    }

    /**
     * @param callable $update
     * @param          $uuid
     * @param array    $data
     * @param array    $option
     * @param array    $param
     *
     * @return mixed
     */
    public function updateData(Callable $update, $uuid, array $data = [], array $option = [], array $param = [])
    {
        return $update($uuid, $data, $option, $param);
    }

    /**
     * @param callable $response
     * @param          $uuid
     * @param          $dataObj
     * @param array    $option
     * @param array    $param
     *
     * @return mixed
     */
    public function updateResponse(Callable $response, $uuid, $dataObj, array $option = [], array $param = [])
    {
        return $response($uuid, $dataObj, $option, $param);
    }
}