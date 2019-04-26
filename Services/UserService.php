<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/27/19 4:12 AM
 */

/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateFromUserRequest;
use App\Components\Scaffold\Services\User\Requests\UpdateFromUserRequest;
use App\Components\Scaffold\Services\User\Responses\CreateUserResponse;
use App\Components\Scaffold\Services\User\Responses\UpdateUserResponse;
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
        $newUser  = $this->createData(new CreateFromUserRequest, $data);
        $user     = $this->userRepository->create($newUser);
        $response = $this->createResponse(new CreateUserResponse, $user, $option, $param);

        return $response;
    }

    public function read()
    {
    }

    public function update($uuid, array $data, array $option = [], array $param = [])
    {
        $updateData = $this->updateData(new UpdateFromUserRequest, $uuid, $data, $option, $param);
        $user       = $this->userRepository->update($uuid, $updateData);
        $response   = $this->updateResponse(new UpdateUserResponse, $uuid, $user, $option, $param);

        return $response;
    }

    public function delete()
    {
    }
}