<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/3/19 7:39 PM
 */

/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateFromUserRequest;
use App\Components\Scaffold\Services\User\Requests\UpdateFromUserRequest;
use App\Components\Scaffold\Services\User\Responses\BrowseUserResponse;
use App\Components\Scaffold\Services\User\Responses\CreateUserResponse;
use App\Components\Scaffold\Services\User\Responses\UpdateUserResponse;
use App\Components\Scaffold\Services\User\Shared\UserCallable;
use Illuminate\Foundation\Application;

/**
 * Class UserService
 * @package App\Components\Scaffold\Services
 */
class UserService extends Service
{
    use UserCallable;

    /**
     * @var \App\Components\Scaffold\Repositories\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param \Illuminate\Foundation\Application                            $app
     * @param \App\Components\Scaffold\Repositories\UserRepositoryInterface $userRepository
     */
    public function __construct(Application $app, UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function browse(array $data = [], array $option = [], array $param = [])
    {
        $users    = $this->userRepository->browse($data, $option, $param);
        $response = $this->browseResponse(new BrowseUserResponse, $users, $option, $param);

        return $response;
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function create(array $data, array $option = [], array $param = []): array
    {
        $newUser  = $this->createData(new CreateFromUserRequest, $data);
        $user     = $this->userRepository->create($newUser, $option, $param);
        $response = $this->createResponse(new CreateUserResponse, $user, $option, $param);

        return $response;
    }

    /**
     *
     */
    public function read()
    {
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function update($uuid, array $data, array $option = [], array $param = [])
    {
        $updateData = $this->updateData(new UpdateFromUserRequest, $uuid, $data['input'], $option, $param);
        $id         = $this->userRepository->getIdBy($uuid) ?? $uuid;
        $user       = $this->userRepository->update($id, $updateData, $option, $param);
        $response   = $this->updateResponse(new UpdateUserResponse, $uuid, $user, $option, $param);

        return $response;
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = [])
    {
        $ids = explode(",", $uuid);

        foreach ($ids as $id) {
            $id = $this->userRepository->getIdBy($id) ?? $id;

            $this->userRepository->delete($id);
        }
    }
}