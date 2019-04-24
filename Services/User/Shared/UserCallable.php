<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 1:20 AM
 */

/**
 * UserCallable.php
 * Created by @anonymoussc on 04/24/2019 4:55 PM.
 */

namespace App\Components\Scaffold\Services\User\Shared;

use App\Components\Scaffold\Services\User\Requests\UserCreateDataRequest;
use App\Components\Scaffold\Services\User\Responses\UserCreateDataResponse;

trait UserCallable
{
    public function createData(UserCreateDataRequest $userCreate, array $data = [], array $option = [], $param = [])
    {
        return $userCreate($data, $option, $param);
    }

    public function createOptionData()
    {
    }

    public function createDataResponse(UserCreateDataResponse $response, $dataObj, array $option = [], array $param = [])
    {
        return $response($dataObj, $option, $param);
    }

    public function createSuccessDataResponse()
    {
    }

    public function createFailDataResponse()
    {
    }
}