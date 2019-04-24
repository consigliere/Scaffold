<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 5:26 AM
 */

/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\UserCreateDataRequest;
use App\Components\Scaffold\Services\User\Responses\UserCreateDataResponse;
use App\Components\Scaffold\Services\User\Shared\UserCallable;
use Illuminate\Foundation\Application;

class UserService extends Service
{
    use UserCallable;

    private $userRepository;

    public function __construct(Application $app, UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function browse()
    {
    }


    public function create(array $data, array $option = [], array $param = [])
    {
        $createData = $this->createData(new UserCreateDataRequest, $data);

        $user = $this->userRepository->create($createData);

        $response = $this->createDataResponse(new UserCreateDataResponse, $user, $option, $param);

        return $response;
    }

    public function read()
    {
    }

    public function update(int $id, array $data, array $option = [], array $param = [])
    {

    }

    public function delete()
    {
    }
}