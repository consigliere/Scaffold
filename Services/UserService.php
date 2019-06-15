<?php
/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/15/19 6:13 PM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateUser;
use App\Components\Scaffold\Services\User\Requests\UpdateUser;
use App\Components\Scaffold\Services\User\Responses\UserCollection;
use App\Components\Scaffold\Services\User\Responses\UserResource;
use App\Components\Signature\Exceptions\BadRequestHttpException;
use App\Components\Signature\Exceptions\NotFoundHttpException;
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

    /**
     * @var \App\Components\Scaffold\Repositories\RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var \Illuminate\Auth\AuthManager|mixed
     */
    private $auth;
    /**
     * @var mixed
     */
    private $request;

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

        $this->auth    = $app->make('auth');
        $this->request = $app->make('request');
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
        $user = $this->userRepository->getById($this->auth->user()->id);

        return (new UserResource)($user);
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

        return (new UserCollection)($users);
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

        if (data_get($data, 'form.roleId') !== null) {
            data_set($data, 'form.roleId', $this->roleRepository->getIdbyUuid(data_get($data, 'form.roleId')));
        }

        $newUser = (new CreateUser)($data);
        $user    = $this->userRepository->create($newUser);

        return (new UserResource)($user);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function read($uuid, array $data = [], array $option = [], array $param = [])
    {
        $id = $this->userRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new BadRequestHttpException('400 Bad Request: Can\'t find User with ID #' . $uuid);
        }

        $user = $this->userRepository->getById($id);

        if (null === $user) {
            throw new NotFoundHttpException('404 Not Found: User can\'t be found in system');
        }

        return (new UserResource)($user);
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
        $data['inList'] = $this->roleRepository->getIds();
        $id             = $this->userRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new BadRequestHttpException('400 Bad Request: Can\'t find User with ID #' . $uuid);
        }

        if (data_get($data, 'form.roleId') !== null) {
            data_set($data, 'form.roleId', $this->roleRepository->getIdbyUuid(data_get($data, 'form.roleId')));
        }

        $newUser = (new UpdateUser)($data);
        $user    = $this->userRepository->update($id, $newUser);

        return (new UserResource)($user);
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $ids = explode(',', $uuid);

        foreach ($ids as $id) {
            $id = $this->userRepository->getIdbyUuid($id);

            if (null === $id) {
                throw new BadRequestHttpException('400 Bad Request: Can\'t find User with ID #' . $uuid);
            }

            $this->userRepository->delete($id);
        }
    }
}