<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/22/19 1:52 AM
 */

/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateUser;
use App\Components\Scaffold\Services\User\Requests\UpdateUser;
use App\Components\Scaffold\Services\User\Responses\UserCollection;
use App\Components\Scaffold\Services\User\Responses\UserResource;
use Illuminate\Foundation\Application;

/**
 * Class UserService
 * @package App\Components\Scaffold\Services
 */
class UserService extends Service
{
    /**
     * @var \App\Components\Scaffold\Repositories\UserRepositoryInterface
     */
    private $userRepository;

    private $roleRepository;

    /**
     * UserService constructor.
     *
     * @param \Illuminate\Foundation\Application                            $app
     * @param \App\Components\Scaffold\Repositories\UserRepositoryInterface $userRepository
     * @param \App\Components\Scaffold\Repositories\RoleRepositoryInterface $roleRepository
     */
    public function __construct(Application $app, UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function profile(array $data = [], array $option = [], array $param = []): array
    {
        $user = $this->userRepository->getById($param['auth.user.id']);

        return (new UserResource)($user, $option, $param);
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
        $users = $this->userRepository->browse($data);

        return (new UserCollection)($users, $option, $param);
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
        $data['inList'] = $this->roleRepository->getIds();
        $newUser        = (new CreateUser)($data);
        $user           = $this->userRepository->create($newUser, $option, $param);

        return (new UserResource)($user, $option, $param);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function read($uuid, array $data, array $option = [], array $param = [])
    {
        $id   = $this->userRepository->getIdbyUuid($uuid) ?? $uuid;
        $user = $this->userRepository->getById($id);

        return (new UserResource)($user, $option, $param);
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
        $id             = $this->userRepository->getIdbyUuid($uuid) ?? $uuid;
        $data['inList'] = $this->roleRepository->getIds();
        $newUser        = (new UpdateUser)($data, $option, $param);
        $user           = $this->userRepository->update($id, $newUser, $option, $param);

        return (new UserResource)($user, $option, $param);
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $ids = explode(',', $uuid);

        foreach ($ids as $id) {
            $id = $this->userRepository->getIdbyUuid($id) ?? $id;

            $this->userRepository->delete($id);
        }
    }
}